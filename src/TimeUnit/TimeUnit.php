<?php

declare(strict_types=1);

namespace Monadial\Duration\TimeUnit;

use Monadial\Duration\TimeUnit\Exception\WrongTimeUnit;

/**
 * A {@link TimeUnit} represents time duration at given unit of
 * granularity and provides utility methods to convert across these units.
 * A {@link TimeUnit} does maintain time information. but only helps
 * organize and use time representation that may be maintained separately
 * across various contexts. A nanosecond is defined as one
 * thousandth of a microsecond, a microsecond as one thousandth of a
 * millisecond, a millisecond as one thousandth of a second, a minute
 * as sixty seconds, an hour as sixty minutes, and a day as twenty-four
 * hours.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
abstract class TimeUnit
{
    // scale

    public const NANO_SCALE = 1; // 1L

    public const MICRO_SCALE = 1_000 * self::NANO_SCALE; // 1000L * 1L

    public const MILLI_SCALE = 1_000 * self::MICRO_SCALE; // 1000L * 1000L

    public const SECOND_SCALE = 1_000 * self::MILLI_SCALE; // 1000L * 1000L

    public const MINUTE_SCALE = 60 * self::SECOND_SCALE; // 60 * 1000L

    public const HOUR_SCALE = 60 * self::MINUTE_SCALE; // 60L * 60L

    public const DAY_SCALE = 24 * self::HOUR_SCALE; // 24L * 60L

    public const MAX_VALUE = PHP_INT_MAX; // 2^63-1

    public const MIN_VALUE = PHP_INT_MIN; //  -2^63

    /**
     * @psalm-readonly
     */
    protected string $name;

    /**
     * @psalm-readonly
     */
    protected int $scale;

    /*
     * Instances cache conversion ratios and saturation cutoffs for the units
     */

    /**
     * @psalm-readonly
     */
    protected int $maxNanos;

    /**
     * @psalm-readonly
     */
    protected int $maxMicros;

    /**
     * @psalm-readonly
     */
    protected int $maxMillis;

    /**
     * @psalm-readonly
     */
    protected int $maxSeconds;

    /**
     * @psalm-readonly
     */
    protected int $maxMinutes;

    /**
     * @psalm-readonly
     */
    protected int $maxHours;

    /**
     * @psalm-readonly
     */
    protected int $maxDays;

    /**
     * @psalm-readonly
     */
    protected int $nanoRatio;

    /**
     * @psalm-readonly
     */
    protected int $microRatio;

    /**
     * @psalm-readonly
     */
    protected int $milliRatio;

    /**
     * @psalm-readonly
     */
    protected int $secondRatio;

    /**
     * @psalm-readonly
     */
    protected int $minuteRatio;

    /**
     * @psalm-readonly
     */
    protected int $hourRatio;

    /**
     * @psalm-readonly
     */
    protected int $dayRatio;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    final public static function Nanoseconds(): Nanoseconds
    {
        return Nanoseconds::make();
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    final public static function Microseconds(): Microseconds
    {
        return Microseconds::make();
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    final public static function Milliseconds(): Milliseconds
    {
        return Milliseconds::make();
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    final public static function Seconds(): Seconds
    {
        return Seconds::make();
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    final public static function Minutes(): Minutes
    {
        return Minutes::make();
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    final public static function Hours(): Hours
    {
        return Hours::make();
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    final public static function Days(): Days
    {
        return Days::make();
    }

    abstract public static function make(): self;

    protected function __construct(int $scale)
    {
        $this->name = strtoupper(static::class);

        $this->scale = $scale;

        // nano
        $this->nanoRatio = self::unitRatio(self::NANO_SCALE, $scale);
        $this->maxNanos = self::maxUnitValue($this->nanoRatio);

        // micro
        $this->microRatio = self::unitRatio(self::MICRO_SCALE, $scale);
        $this->maxMicros = self::maxUnitValue($this->microRatio);

        // millis
        $this->milliRatio = self::unitRatio(self::MILLI_SCALE, $scale);
        $this->maxMillis = self::maxUnitValue($this->milliRatio);

        // seconds
        $this->secondRatio = self::unitRatio(self::SECOND_SCALE, $scale);
        $this->maxSeconds = self::maxUnitValue($this->secondRatio);

        // minutes
        $this->minuteRatio = self::unitRatio(self::MINUTE_SCALE, $scale);
        $this->maxMinutes = self::maxUnitValue($this->secondRatio);

        // minutes
        $this->hourRatio = self::unitRatio(self::HOUR_SCALE, $scale);
        $this->maxHours = self::maxUnitValue($this->hourRatio);

        // minutes
        $this->dayRatio = self::unitRatio(self::DAY_SCALE, $scale);
        $this->maxDays = self::maxUnitValue($this->dayRatio);
    }

    public function maxValue(): int
    {
        switch (true) {
            case $this instanceof Nanoseconds:
                return $this->maxNanos;
            case $this instanceof Microseconds:
                return $this->maxMicros;
            case $this instanceof Milliseconds:
                return $this->maxMillis;
            case $this instanceof Seconds:
                return $this->maxSeconds;
            case $this instanceof Minutes:
                return $this->maxMinutes;
            case $this instanceof Hours:
                return $this->maxHours;
            case $this instanceof Days:
                return $this->maxDays;
            default:
                throw new WrongTimeUnit($this);
        }
    }

    /**
     * Convert TimeUnit to Nanoseconds
     *
     * @param int $duration the duration
     * @return int converted duration
     *
     * {@see TimeUnit::MAX_VALUE} if conversion would negatively overflow,
     * {@see TimeUnit::MIN_VALUE} if it would positively overflow.
     */
    // phpcs:ignore SlevomatCodingStandard.Complexity.Cognitive.ComplexityTooHigh
    public function toNanos(int $duration): int
    {
        $fun = function (int $duration): float {
            if ($this->scale === self::NANO_SCALE) {
                return $duration;
            }

            if ($duration > $this->maxNanos) {
                return self::MAX_VALUE;
            }

            if ($duration < -$this->maxNanos) {
                return (float)self::MIN_VALUE;
            }

            return $duration * $this->nanoRatio;
        };

        return (int)$fun($duration);
    }

    /**
     * Convert TimeUnit to Microseconds
     *
     * @param int $duration the duration
     * @return int converted duration
     *
     * {@see TimeUnit::MAX_VALUE} if conversion would negatively overflow,
     * {@see TimeUnit::MIN_VALUE} if it would positively overflow.
     * @phpcsSuppress
     */
    // phpcs:ignore SlevomatCodingStandard.Complexity.Cognitive.ComplexityTooHigh
    public function toMicros(int $duration): int
    {
        $fun = function (int $duration): float {
            if ($this->scale <= self::MICRO_SCALE) {
                return $duration / $this->microRatio;
            }

            if ($duration > $this->maxMicros) {
                return self::MAX_VALUE;
            }

            if ($duration < -$this->maxMicros) {
                return (float)self::MIN_VALUE;
            }

            return $duration * $this->microRatio;
        };

        return (int)$fun($duration);
    }

    /**
     * Convert TimeUnit to Milliseconds
     *
     * @param int $duration the duration
     * @return int converted duration
     *
     * {@see TimeUnit::MAX_VALUE} if conversion would negatively overflow,
     * {@see TimeUnit::MIN_VALUE} if it would positively overflow.
     */
    // phpcs:ignore SlevomatCodingStandard.Complexity.Cognitive.ComplexityTooHigh
    public function toMillis(int $duration): int
    {
        $fun = function (int $duration): float {
            if ($this->scale <= self::MILLI_SCALE) {
                return $duration / $this->milliRatio;
            }

            if ($duration > $this->maxMillis) {
                return self::MAX_VALUE;
            }

            if ($duration < -$this->maxMillis) {
                return (float)self::MIN_VALUE;
            }

            return $duration * $this->milliRatio;
        };

        return (int)$fun($duration);
    }

    /**
     * Convert TimeUnit to Seconds
     *
     * @param int $duration the duration
     * @return int converted duration
     *
     * {@see TimeUnit::MAX_VALUE} if conversion would negatively overflow,
     * {@see TimeUnit::MIN_VALUE} if it would positively overflow.
     */
    // phpcs:ignore SlevomatCodingStandard.Complexity.Cognitive.ComplexityTooHigh
    public function toSeconds(int $duration): int
    {
        $fun = function (int $duration): float {
            if ($this->scale <= self::SECOND_SCALE) {
                return $duration / $this->secondRatio;
            }

            if ($duration > $this->maxSeconds) {
                return self::MAX_VALUE;
            }

            if ($duration < -$this->maxSeconds) {
                return (float)self::MIN_VALUE;
            }

            return $duration * $this->secondRatio;
        };

        return (int)$fun($duration);
    }

    /**
     * Convert TimeUnit to Minutes
     *
     * @param int $duration the duration
     * @return int converted duration
     *
     * {@see TimeUnit::MAX_VALUE} if conversion would negatively overflow,
     * {@see TimeUnit::MIN_VALUE} if it would positively overflow.
     */
    public function toMinutes(int $duration): int
    {
        return self::cvt($duration, self::MINUTE_SCALE, $this->scale);
    }

    /**
     * Convert TimeUnit to Hours
     *
     * @param int $duration the duration
     * @return int converted duration
     *
     * {@see TimeUnit::MAX_VALUE} if conversion would negatively overflow,
     * {@see TimeUnit::MIN_VALUE} if it would positively overflow.
     */
    public function toHours(int $duration): int
    {
        return self::cvt($duration, self::HOUR_SCALE, $this->scale);
    }

    /**
     * Convert TimeUnit to Days
     *
     * @param int $duration the duration
     * @return int converted duration
     *
     * {@see TimeUnit::MAX_VALUE} if conversion would negatively overflow,
     * {@see TimeUnit::MIN_VALUE} if it would positively overflow.
     */
    public function toDays(int $duration): int
    {
        return self::cvt($duration, self::DAY_SCALE, $this->scale);
    }

    public function convert(int $sourceDuration, self $sourceUnit): int
    {
        switch (true) {
            case $this instanceof Nanoseconds:
                return $sourceUnit->toNanos($sourceDuration);
            case $this instanceof Microseconds:
                return $sourceUnit->toMicros($sourceDuration);
            case $this instanceof Milliseconds:
                return $sourceUnit->toMillis($sourceDuration);
            case $this instanceof Seconds:
                return $sourceUnit->toSeconds($sourceDuration);
            default:
                return self::cvt($sourceDuration, $this->scale, $sourceUnit->scale);
        }
    }

    public function equals(self $timeUnit): bool
    {
        return $this->scale === $timeUnit->scale;
    }

    /**
     * @psalm-pure
     */
    private static function maxUnitValue(int $scale): int
    {
        return (int)(self::MAX_VALUE / $scale);
    }

    /**
     * @psalm-pure
     */
    private static function unitRatio(int $scale, int $unitScale): int
    {
        $result = static fn (
            int $scale,
            int $unitScale
        ): float => $scale >= $unitScale ? $scale / $unitScale : $unitScale / $scale;

        return (int)$result($scale, $unitScale);
    }

    /**
     * @psalm-pure
     */
    // phpcs:ignore SlevomatCodingStandard.Complexity.Cognitive.ComplexityTooHigh
    private static function cvt(int $duration, int $destinationUnitScale, int $sourceUnitScale): int
    {
        // this is dirt hack how to resolve proper type conversion from float to int
        $fun = static function (int $duration, int $destinationUnitScale, int $sourceUnitScale): float {
            // lazy computation
            $ratioFn = static fn (int $source, int $dest): int => (int)($source / $dest);
            $maximumFn = static fn (int $ratio): int => (int)(self::MAX_VALUE / $ratio);

            if ($sourceUnitScale === $destinationUnitScale) {
                return $duration;
            }

            if ($sourceUnitScale < $destinationUnitScale) {
                return $duration / ($destinationUnitScale / $sourceUnitScale);
            }

            $ratio = $ratioFn($sourceUnitScale, $destinationUnitScale);
            $maximum = $maximumFn($ratio);

            if ($duration > $maximum) {
                return self::MAX_VALUE;
            }

            if ($duration < -$maximum) {
                return (float)self::MIN_VALUE;
            }

            return $duration * $ratio;
        };

        return (int)$fun($duration, $destinationUnitScale, $sourceUnitScale);
    }

    public function __toString(): string
    {
        return static::class;
    }
}
