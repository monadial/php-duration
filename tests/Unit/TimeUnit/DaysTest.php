<?php

declare(strict_types=1);

/*
 * @author tmihalicka
 * @copyright PIXEL FEDERATION
 * @license Internal use only
 */

namespace Monadial\Duration\Tests\TimeUnit;

use Monadial\Duration\TimeUnit\Days;
use Monadial\Duration\TimeUnit\TimeUnit;
use PHPUnit\Framework\TestCase;

class DaysTest extends TestCase
{
    private Days $days;

    public function testToNanos(): void
    {
        self::assertEquals(24 * 60 * 60 * 1000 * 1000 * 1000, $this->days->toNanos(1));
    }

    public function testToMicros(): void
    {
        self::assertEquals(24 * 60 * 60 * 1000 * 1000, $this->days->toMicros(1));
    }

    public function testToMillis(): void
    {
        self::assertEquals(24 * 60 * 60 * 1000, $this->days->toMillis(1));
    }

    public function testToSeconds(): void
    {
        self::assertEquals(24 * 60 * 60, $this->days->toSeconds(1));
    }

    public function testToMinutes(): void
    {
        self::assertEquals(24 * 60, $this->days->toMinutes(1));
    }

    public function testToHours(): void
    {
        self::assertEquals(24, $this->days->toHours(1));
    }

    public function testToDays(): void
    {
        self::assertEquals(1, $this->days->toDays(1));
    }

    public function testConversion(): void
    {
        self::assertEquals(1, $this->days->convert(1, TimeUnit::Days()));
        self::assertEquals(1, $this->days->convert(24, TimeUnit::Hours()));
        self::assertEquals(1, $this->days->convert(24 * 60, TimeUnit::Minutes()));
        self::assertEquals(1, $this->days->convert(24 * 60 * 60, TimeUnit::Seconds()));
        self::assertEquals(1, $this->days->convert(24 * 60 * 60 * 1000, TimeUnit::Milliseconds()));
        self::assertEquals(1, $this->days->convert(24 * 60 * 60 * 1000 * 1000, TimeUnit::Microseconds()));
        self::assertEquals(1, $this->days->convert(24 * 60 * 60 * 1000 * 1000 * 1000, TimeUnit::Nanoseconds()));
    }

    public function testEquals(): void
    {
        self::assertTrue($this->days->equals(TimeUnit::Days()));
        self::assertFalse($this->days->equals(TimeUnit::Minutes()));
    }

    protected function setUp(): void
    {
        $this->days = TimeUnit::Days();
    }
}
