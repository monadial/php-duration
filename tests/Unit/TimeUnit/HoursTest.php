<?php

declare(strict_types=1);

/*
 * @author tmihalicka
 * @copyright PIXEL FEDERATION
 * @license Internal use only
 */

namespace Monadial\Duration\Tests\TimeUnit;

use Monadial\Duration\TimeUnit\Hours;
use Monadial\Duration\TimeUnit\TimeUnit;
use PHPUnit\Framework\TestCase;

class HoursTest extends TestCase
{
    private Hours $hours;

    public function testToNanos(): void
    {
        self::assertEquals(1000 * 1000 * 1000 * 60 * 60, $this->hours->toNanos(1));
    }

    public function testToMicros(): void
    {
        self::assertEquals(1000 * 1000 * 60 * 60, $this->hours->toMicros(1));
    }

    public function testToMillis(): void
    {
        self::assertEquals(1000 * 60 * 60, $this->hours->toMillis(1));
    }

    public function testToSeconds(): void
    {
        self::assertEquals(60 * 60, $this->hours->toSeconds(1));
    }

    public function testToMinutes(): void
    {
        self::assertEquals(60, $this->hours->toMinutes(1));
    }

    public function testToHours(): void
    {
        self::assertEquals(1, $this->hours->toHours(1));
    }

    public function testToDays(): void
    {
        self::assertEquals(1, $this->hours->toDays(24));
    }

    public function testConversion(): void
    {
        self::assertEquals(24, $this->hours->convert(1, TimeUnit::Days()));
        self::assertEquals(1, $this->hours->convert(1, TimeUnit::Hours()));
        self::assertEquals(1, $this->hours->convert(60, TimeUnit::Minutes()));
        self::assertEquals(1, $this->hours->convert(60 * 60, TimeUnit::Seconds()));
        self::assertEquals(1, $this->hours->convert(60 * 60 * 1000, TimeUnit::Milliseconds()));
        self::assertEquals(1, $this->hours->convert(60 * 60 * 1000 * 1000, TimeUnit::Microseconds()));
        self::assertEquals(1, $this->hours->convert(60 * 60 * 1000 * 1000 * 1000, TimeUnit::Nanoseconds()));
    }

    public function testEquals(): void
    {
        self::assertTrue($this->hours->equals(TimeUnit::Hours()));
        self::assertFalse($this->hours->equals(TimeUnit::Minutes()));
    }

    protected function setUp(): void
    {
        $this->hours = TimeUnit::Hours();
    }
}
