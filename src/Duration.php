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
    abstract public function add(self $other): self;

    /**
     * Return the difference of that duration and this.
     */
    abstract public function subtract(self $other): self;

    /**
     * Return this duration multiplied by the scalar factor.
     */
    abstract public function multiply(int $factor): self;

    /**
     * Return this duration divided by the scalar factor.
     */
    abstract public function division(int $divisor): self;

    /**
     * Return the number of nanoseconds as floating point number, scaled down to the given unit.
     */
    abstract public function toUnit(TimeUnit $unit): FiniteDuration;

    /**
     * Factorise this duration.
     */
    abstract public function factor(int $factor): self;

    /**
     * Negate this duration.
     */
    abstract public function unary(): self;

    /**
     * Determines duration is finite
     *
     * //todo add infinite duration
     */
    abstract public function isFinite(): bool;

    /**
     * Returns true if this duration is equals to other duration
     */
    abstract public function equals(self $other): bool;

    /**
     * Return duration which is equal to this duration but with a coarsest Unit,
     * or self in case it is already the coarsest Unit
     */
    abstract public function toCoarsest(): self;

    public function __construct(int $length, TimeUnit $unit)
    {
        $this->unit = $unit;
        $this->length = $length;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function timeUnitName(): string
    {
        $timeUnitsNames = FiniteDurationStringParser::timeUnits();

        return $timeUnitsNames[get_class($this->unit)][0] . ($this->length === 1 ? "" : "s");
    }

    public function __toString(): string
    {
        return sprintf("%d %s", $this->length, $this->timeUnitName());
    }
}
