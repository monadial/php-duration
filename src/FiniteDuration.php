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

    public static function fromTimeUnit(int $length, TimeUnit $unit): self
    {
        return new self($length, $unit);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function fromNanos(int $nanos): self
    {
        if ($nanos > (TimeUnit::MAX_VALUE - 1) || $nanos < TimeUnit::MIN_VALUE + 1) {
            throw new InvalidArgumentException(sprintf("trying to construct too large duration with %s ns", $nanos));
        }

        switch (true) {
            case $nanos % TimeUnit::DAY_SCALE === 0:
                return new self((int)($nanos / TimeUnit::DAY_SCALE), TimeUnit::Days());
            case $nanos % TimeUnit::HOUR_SCALE === 0:
                return new self((int)($nanos / TimeUnit::HOUR_SCALE), TimeUnit::Hours());
            case $nanos % TimeUnit::MINUTE_SCALE === 0:
                return new self((int)($nanos / TimeUnit::MINUTE_SCALE), TimeUnit::Minutes());
            case $nanos % TimeUnit::SECOND_SCALE === 0:
                return new self((int)($nanos / TimeUnit::SECOND_SCALE), TimeUnit::Seconds());
            case $nanos % TimeUnit::MILLI_SCALE === 0:
                return new self((int)($nanos / TimeUnit::MILLI_SCALE), TimeUnit::Milliseconds());
            case $nanos % TimeUnit::MICRO_SCALE === 0:
                return new self((int)($nanos / TimeUnit::MICRO_SCALE), TimeUnit::Microseconds());
            default:
                return new self($nanos, TimeUnit::Nanoseconds());
        }
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function fromString(string $duration): self
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

    public function add(Duration $other): self
    {
        return $this->addOther($other->length, $other->unit);
    }

    public function subtract(Duration $other): self
    {
        return $this->addOther(-$other->length, $other->unit);
    }

    public function multiply(int $factor): self
    {
        return self::fromNanos($this->toNanos() * $factor);
    }

    public function division(int $divisor): self
    {
        return self::fromNanos((int)($this->toNanos() / $divisor));
    }

    public function toUnit(TimeUnit $unit): self
    {
        return self::fromNanos($unit->convert($this->toNanos(), $unit));
    }

    public function factor(int $factor): self
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

        $loop = static function (int $length, TimeUnit $unit) use (&$loop, $self): self {
            $coarseOrThis = static function (
                int $divider,
                TimeUnit $coarser
            ) use (
                &$loop,
                $length,
                $unit,
                $self
            ): self {
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

                return new self($length, $unit);
            };

            switch (true) {
                case $unit instanceof Days:
                    return new self($length, $unit);
                case $unit instanceof Hours:
                    return $coarseOrThis(24, TimeUnit::Days());
                case $unit instanceof Minutes:
                    return $coarseOrThis(60, TimeUnit::Hours());
                case $unit instanceof Seconds:
                    return $coarseOrThis(60, TimeUnit::Minutes());
                case $unit instanceof Milliseconds:
                    return $coarseOrThis(1_000, TimeUnit::Seconds());
                case $unit instanceof Microseconds:
                    return $coarseOrThis(1_000, TimeUnit::Milliseconds());
                case $unit instanceof Nanoseconds:
                    return $coarseOrThis(1_000, TimeUnit::Microseconds());
                default:
                    throw new WrongTimeUnit($unit);
            }
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
    private function addOther(int $otherLength, TimeUnit $otherUnit): self
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

        $totalLength = $safeAdd(
            $commonUnit->convert($this->length, $this->unit),
            $commonUnit->convert($otherLength, $otherUnit)
        );

        return new self($totalLength, $commonUnit);
    }

    private function validateBoundary(int $length, TimeUnit $unit): void
    {
        if (-$unit->maxValue() <= $length && $length <= $unit->maxValue()) {
            return;
        }

        throw new FiniteDurationBoundary();
    }
}
