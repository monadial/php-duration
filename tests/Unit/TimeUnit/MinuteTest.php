<?php

declare(strict_types=1);

/*
 * @author tmihalicka
 * @copyright PIXEL FEDERATION
 * @license Internal use only
 */

namespace Monadial\Duration\Tests\TimeUnit;

use Monadial\Duration\TimeUnit\Minutes;
use Monadial\Duration\TimeUnit\TimeUnit;
use PHPUnit\Framework\TestCase;

class MinuteTest extends TestCase
{
    private Minutes $minutes;

    public function testToNanos(): void
    {
        self::assertEquals(60 * 1000 * 1000 * 1000, $this->minutes->toNanos(1));
    }

    public function testToMicros(): void
    {
        self::assertEquals(60 * 1000 * 1000, $this->minutes->toMicros(1));
    }

    public function testToMillis(): void
    {
        self::assertEquals(60 * 1000, $this->minutes->toMillis(1));
    }

    public function testToSeconds(): void
    {
        self::assertEquals(60, $this->minutes->toSeconds(1));
    }

    public function testToMinutes(): void
    {
        self::assertEquals(1, $this->minutes->toMinutes(1));
    }

    public function testToHours(): void
    {
        self::assertEquals(1, $this->minutes->toHours(60));
    }

    public function testToDays(): void
    {
        self::assertEquals(1, $this->minutes->toDays(60 * 24));
    }

    public function testConversion(): void
    {
        self::assertEquals(24 * 60, $this->minutes->convert(1, TimeUnit::Days()));
        self::assertEquals(60, $this->minutes->convert(1, TimeUnit::Hours()));
        self::assertEquals(1, $this->minutes->convert(1, TimeUnit::Minutes()));
        self::assertEquals(1, $this->minutes->convert(60, TimeUnit::Seconds()));
        self::assertEquals(1, $this->minutes->convert(60 * 1000, TimeUnit::Milliseconds()));
        self::assertEquals(1, $this->minutes->convert(60 * 1000 * 1000, TimeUnit::Microseconds()));
        self::assertEquals(1, $this->minutes->convert(60 * 1000 * 1000 * 1000, TimeUnit::Nanoseconds()));
    }

    public function testEquals(): void
    {
        $value1 = TimeUnit::Minutes();
        $value2 = TimeUnit::Minutes();
        $value3 = TimeUnit::Hours();
        self::assertTrue($value2->equals($value1));
        self::assertFalse($value3->equals($value1));
    }

    protected function setUp(): void
    {
        $this->minutes = TimeUnit::Minutes();
    }
}
