<?php

declare(strict_types=1);

namespace Monadial\Duration;

use Monadial\Duration\TimeUnit\TimeUnit;

final class Deadline
{
    private FiniteDuration $time;

    private function __construct(FiniteDuration $time)
    {
        $this->time = $time;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function now(): Deadline
    {
        return new static(FiniteDuration::fromTimeUnit((int) hrtime(true), TimeUnit::Nanoseconds()));
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function create(FiniteDuration $time): Deadline
    {
        return new self($time);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function add(FiniteDuration $other): Deadline
    {
        return new static($this->time->add($other));
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function subtract(FiniteDuration $other): Deadline
    {
        return new static($this->time->subtract($other));
    }

    public function subtractDeadline(Deadline $other): FiniteDuration
    {
        return $this->time->subtract($other->time);
    }

    public function timeLeft(): FiniteDuration
    {
        return $this->time->subtract(self::now()->time);
    }

    public function hasTimeLeft(): bool
    {
        return !$this->isOverdue();
    }

    public function isOverdue(): bool
    {
        return $this->time->toNanos() - hrtime(true) < 0;
    }
}
