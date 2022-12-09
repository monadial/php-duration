<?php

declare(strict_types=1);

namespace Monadial\Duration;

use Monadial\Duration\TimeUnit\TimeUnit;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
abstract class Duration
{
    protected int $length;

    protected TimeUnit $unit;

    /**
     * Return the length of this duration measured in whole nanoseconds, rounding towards zero.
     */
    abstract public function toNanos(): int;

    /**
     * Return the length of this duration measured in whole microseconds, rounding towards zero.
     */
    abstract public function toMicros(): int;

    /**
     * Return the length of this duration measured in whole milliseconds, rounding towards zero.
     */
    abstract public function toMillis(): int;

    /**
     * Return the length of this duration measured in whole seconds, rounding towards zero.
     */
    abstract public function toSeconds(): int;

    /**
     * Return the length of this duration measured in whole minutes, rounding towards zero.
     */
    abstract public function toMinutes(): int;

    /**
     * Return the length of this duration measured in whole hours, rounding towards zero.
     */
    abstract public function toHours(): int;

    /**
     * Return the length of this duration measured in whole days, rounding towards zero.
     */
    abstract public function toDays(): int;

    /**
     * Return the sum of that duration and this.
     */
    abstract public function add(Duration $other): Duration;

    /**
     * Return the difference of that duration and this.
     */
    abstract public function subtract(Duration $other): Duration;

    /**
     * Return this duration multiplied by the scalar factor.
     */
    abstract public function multiply(int $factor): Duration;

    /**
     * Return this duration divided by the scalar factor.
     */
    abstract public function division(int $divisor): Duration;

    /**
     * Return the number of nanoseconds as floating point number, scaled down to the given unit.
     */
    abstract public function toUnit(TimeUnit $unit): float;

    /**
     * Factorise this duration.
     */
    abstract public function factor(int $factor): Duration;

    /**
     * Negate this duration.
     */
    abstract public function unary(): Duration;

    /**
     * Determines duration is finite
     *
     * //todo add infinite duration
     */
    abstract public function isFinite(): bool;

    /**
     * Returns true if this duration is equals to other duration
     */
    abstract public function equals(Duration $other): bool;

    /**
     * Return duration which is equal to this duration but with a coarsest Unit,
     * or self in case it is already the coarsest Unit
     */
    abstract public function toCoarsest(): Duration;

    public function __construct(int $length, TimeUnit $unit)
    {
        $this->length = $length;
        $this->unit = $unit;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function timeUnitName(): string
    {
        $timeUnitsNames = FiniteDurationStringParser::timeUnitName();

        return $timeUnitsNames[get_class($this->unit)] . ($this->length === 1 ? "" : "s");
    }

    public function __toString(): string
    {
        return sprintf("%d %s", $this->length, $this->timeUnitName());
    }
}
