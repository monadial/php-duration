<?php

declare(strict_types=1);

namespace Monadial\Duration;

use DateTimeImmutable;
use InvalidArgumentException;
use Monadial\Duration\Exception\FiniteDurationBoundary;
use Monadial\Duration\Exception\NanosecondsAreNotConvertibleToDateTime;
use Monadial\Duration\Exception\UnableToConvertToDateTime;
use Monadial\Duration\TimeUnit\Days;
use Monadial\Duration\TimeUnit\Exception\WrongTimeUnit;
use Monadial\Duration\TimeUnit\Hours;
use Monadial\Duration\TimeUnit\Microseconds;
use Monadial\Duration\TimeUnit\Milliseconds;
use Monadial\Duration\TimeUnit\Minutes;
use Monadial\Duration\TimeUnit\Nanoseconds;
use Monadial\Duration\TimeUnit\Seconds;
use Monadial\Duration\TimeUnit\TimeUnit;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class FiniteDuration extends Duration
{
    public function __construct(int $length, TimeUnit $unit)
    {
        $this->validateBoundary($length, $unit);

        parent::__construct($length, $unit);
    }

    public static function fromTimeUnit(int $length, TimeUnit $unit): FiniteDuration
    {
        return new self($length, $unit);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function fromNanos(int $nanos): FiniteDuration
    {
        if ($nanos > (TimeUnit::MAX_VALUE - 1) || $nanos < TimeUnit::MIN_VALUE + 1) {
            throw new InvalidArgumentException(sprintf("trying to construct too large duration with %s ns", $nanos));
        }

        return match (true) {
            $nanos % TimeUnit::DAY_SCALE === 0 =>
                new self((int)($nanos / TimeUnit::DAY_SCALE), TimeUnit::Days()),
            $nanos % TimeUnit::HOUR_SCALE === 0 =>
                new self((int)($nanos / TimeUnit::HOUR_SCALE), TimeUnit::Hours()),
            $nanos % TimeUnit::MINUTE_SCALE === 0 =>
                new self((int)($nanos / TimeUnit::MINUTE_SCALE), TimeUnit::Minutes()),
            $nanos % TimeUnit::SECOND_SCALE === 0 =>
                new self((int)($nanos / TimeUnit::SECOND_SCALE), TimeUnit::Seconds()),
            $nanos % TimeUnit::MILLI_SCALE === 0 =>
                new self((int)($nanos / TimeUnit::MILLI_SCALE), TimeUnit::Milliseconds()),
            $nanos % TimeUnit::MICRO_SCALE === 0 =>
                new self((int)($nanos / TimeUnit::MICRO_SCALE), TimeUnit::Microseconds()),
            $nanos % TimeUnit::NANO_SCALE === 0 =>
                new self($nanos, TimeUnit::Nanoseconds()),
            default =>
            throw new InvalidArgumentException(sprintf("unable to convert %s ns to FiniteDuration", $nanos)),
        };
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function fromString(string $duration): FiniteDuration
    {
        return FiniteDurationStringParser::parse($duration);
    }

    public function toNanos(): int
    {
        return $this->unit->toNanos($this->length);
    }

    public function toMicros(): int
    {
        return $this->unit->toMicros($this->length);
    }

    public function toMillis(): int
    {
        return $this->unit->toMillis($this->length);
    }

    public function toSeconds(): int
    {
        return $this->unit->toSeconds($this->length);
    }

    public function toMinutes(): int
    {
        return $this->unit->toMinutes($this->length);
    }

    public function toHours(): int
    {
        return $this->unit->toHours($this->length);
    }

    public function toDays(): int
    {
        return $this->unit->toDays($this->length);
    }

    public function add(Duration $other): FiniteDuration
    {
        return $this->addOther($other->length, $other->unit);
    }

    public function subtract(Duration $other): FiniteDuration
    {
        return $this->addOther(-$other->length, $other->unit);
    }

    public function multiply(int $factor): FiniteDuration
    {
        return self::fromNanos($this->toNanos() * $factor);
    }

    public function division(int $divisor): FiniteDuration
    {
        return self::fromNanos((int)($this->toNanos() / $divisor));
    }

    public function toUnit(TimeUnit $unit): FiniteDuration
    {
        return self::fromNanos($unit->convert($this->toNanos(), $unit));
    }

    public function factor(int $factor): FiniteDuration
    {
        $safeMul = static function (int $aNum, int $bNum): int {
            $aAbs = abs($aNum);
            $bAbs = abs($bNum);

            // product of multiplication
            return $aAbs * $bAbs;
        };

        return new self($safeMul($this->length, $factor), $this->unit);
    }

    public function unary(): Duration
    {
        return new self(-$this->length, $this->unit);
    }

    public function isFinite(): bool
    {
        return true;
    }

    public function equals(Duration $other): bool
    {
        return $other->toNanos() === $this->toNanos();
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    // phpcs:ignore SlevomatCodingStandard.Functions.FunctionLength.FunctionLength
    public function toCoarsest(): Duration
    {
        // reference to this, for static closure binding
        $self = $this;

        $loop = static function (int $length, TimeUnit $unit) use (&$loop, $self): FiniteDuration {
            $coarseOrThis = static function (int $divider, TimeUnit $coarser) use (&$loop, $length, $unit, $self): FiniteDuration {
                if ($length % $divider === 0) {
                    /**
                     * @var callable(int, TimeUnit): FiniteDuration $typedLoop
                     */
                    $typedLoop = $loop;

                    return $typedLoop((int)($length / $divider), $coarser);
                }

                if ($unit->equals($self->unit)) {
                    return $self;
                }

                return new FiniteDuration($length, $unit);
            };

            return match (true) {
                $unit instanceof Days => new FiniteDuration($length, $unit),
                $unit instanceof Hours => $coarseOrThis(24, TimeUnit::Days()),
                $unit instanceof Minutes => $coarseOrThis(60, TimeUnit::Hours()),
                $unit instanceof Seconds => $coarseOrThis(60, TimeUnit::Minutes()),
                $unit instanceof Milliseconds => $coarseOrThis(1_000, TimeUnit::Seconds()),
                $unit instanceof Microseconds => $coarseOrThis(1_000, TimeUnit::Milliseconds()),
                $unit instanceof Nanoseconds => $coarseOrThis(1_000, TimeUnit::Microseconds()),
                default => throw new WrongTimeUnit($unit),
            };
        };

        if ($this->unit instanceof Days || $this->length === 0) {
            return $this;
        }

        return $loop($this->length, $this->unit);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function asDateTime(): DateTimeImmutable
    {
        if ($this->unit->equals(Nanoseconds::make())) {
            throw new NanosecondsAreNotConvertibleToDateTime();
        }

        $now = new DateTimeImmutable('now');

        $maybeConverted = $now->modify(sprintf('+%s %s', $this->length, $this->timeUnitName()));

        // fallback to the smallest possible unit
        if ($maybeConverted === false) {
            throw new UnableToConvertToDateTime($this);
        }

        return $maybeConverted;
    }

    /**
     * @phpcsSuppress
     */
    //phpcs:ignore SlevomatCodingStandard.Complexity.Cognitive.ComplexityTooHigh
    private function addOther(int $otherLength, TimeUnit $otherUnit): FiniteDuration
    {
        /**
         * @psalm-pure
         */
        $safeAdd = static function (int $aNum, int $bNum): int {
            $maxBoundary = (($bNum > 0) && ($aNum > TimeUnit::MAX_VALUE - 1 - $bNum));
            $minBoundary = (($bNum < 0) && ($aNum < TimeUnit::MIN_VALUE + 1 - $bNum));


            if ($maxBoundary || $minBoundary) {
                throw new InvalidArgumentException('integer overflow');
            }

            return $aNum + $bNum;
        };

        $commonUnit = $otherUnit->convert(1, $this->unit) === 0 ? $this->unit : $otherUnit;

        $totalLength = $safeAdd($commonUnit->convert($this->length, $this->unit), $commonUnit->convert($otherLength, $otherUnit));

        return new static($totalLength, $commonUnit);
    }

    private function validateBoundary(int $length, TimeUnit $unit): void
    {
        if (-$unit->maxValue() <= $length && $length <= $unit->maxValue()) {
            return;
        }

        throw new FiniteDurationBoundary();
    }
}
