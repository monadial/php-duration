<?php

declare(strict_types=1);

/*
 * @author tmihalicka
 * @copyright PIXEL FEDERATION
 * @license Internal use only
 */

namespace Monadial\Duration\Tests\TimeUnit;

use Monadial\Duration\TimeUnit\Seconds;
use Monadial\Duration\TimeUnit\TimeUnit;
use PHPUnit\Framework\TestCase;

class SecondsTest extends TestCase
{
    private Seconds $seconds;

    public function testToNanos(): void
    {
        self::assertEquals(1_000_000_000, $this->seconds->toNanos(1));
    }

    public function testToMicros(): void
    {
        self::assertEquals(1_000_000, $this->seconds->toMicros(1));
    }

    public function testToMillis(): void
    {
        self::assertEquals(1_000, $this->seconds->toMillis(1));
    }

    public function testToSeconds(): void
    {
        self::assertEquals(1, $this->seconds->toSeconds(1));
    }

    public function testToMinutes(): void
    {
        self::assertEquals(1, $this->seconds->toMinutes(60));
    }

    public function testToHours(): void
    {
        self::assertEquals(1, $this->seconds->toHours(60 * 60));
    }

    public function testToDays(): void
    {
        self::assertEquals(1, $this->seconds->toDays(60 * 60 * 24));
    }

    public function testConversion(): void
    {
        self::assertEquals(24 * 60 * 60, $this->seconds->convert(1, TimeUnit::Days()));
        self::assertEquals(60 * 60, $this->seconds->convert(1, TimeUnit::Hours()));
        self::assertEquals(60, $this->seconds->convert(1, TimeUnit::Minutes()));
        self::assertEquals(1, $this->seconds->convert(1, TimeUnit::Seconds()));
        self::assertEquals(1, $this->seconds->convert(1000, TimeUnit::Milliseconds()));
        self::assertEquals(1, $this->seconds->convert(1000 * 1000, TimeUnit::Microseconds()));
        self::assertEquals(1, $this->seconds->convert(1000 * 1000 * 1000, TimeUnit::Nanoseconds()));
    }

    public function testEquals(): void
    {
        self::assertTrue($this->seconds->equals(TimeUnit::Seconds()));
        self::assertFalse($this->seconds->equals(TimeUnit::Minutes()));
    }

    protected function setUp(): void
    {
        $this->seconds = TimeUnit::Seconds();
    }
}
