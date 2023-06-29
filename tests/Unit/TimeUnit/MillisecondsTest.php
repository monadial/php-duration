<?php

declare(strict_types=1);

/*
 * @author tmihalicka
 * @copyright PIXEL FEDERATION
 * @license Internal use only
 */

namespace Monadial\Duration\Tests\TimeUnit;

use Monadial\Duration\TimeUnit\Milliseconds;
use Monadial\Duration\TimeUnit\TimeUnit;
use PHPUnit\Framework\TestCase;

class MillisecondsTest extends TestCase
{
    private Milliseconds $milliseconds;

    public function testToNanos(): void
    {
        self::assertEquals(1000 * 1000, $this->milliseconds->toNanos(1));
    }

    public function testToMicros(): void
    {
        self::assertEquals(1000, $this->milliseconds->toMicros(1));
    }

    public function testToMillis(): void
    {
        self::assertEquals(1, $this->milliseconds->toMillis(1));
    }

    public function testToSeconds(): void
    {
        self::assertEquals(1, $this->milliseconds->toSeconds(1000));
    }

    public function testToMinutes(): void
    {
        self::assertEquals(1, $this->milliseconds->toMinutes(1000 * 60));
    }

    public function testToHours(): void
    {
        self::assertEquals(1, $this->milliseconds->toHours(1000 * 60 * 60));
    }

    public function testToDays(): void
    {
        self::assertEquals(1, $this->milliseconds->toDays(1000 * 60 * 60 * 24));
    }

    public function testConversion(): void
    {
        self::assertEquals(24 * 60 * 60 * 1000, $this->milliseconds->convert(1, TimeUnit::Days()));
        self::assertEquals(60 * 60 * 1000, $this->milliseconds->convert(1, TimeUnit::Hours()));
        self::assertEquals(60 * 1000, $this->milliseconds->convert(1, TimeUnit::Minutes()));
        self::assertEquals(1000, $this->milliseconds->convert(1, TimeUnit::Seconds()));
        self::assertEquals(1, $this->milliseconds->convert(1, TimeUnit::Milliseconds()));
        self::assertEquals(1, $this->milliseconds->convert(1000, TimeUnit::Microseconds()));
        self::assertEquals(1, $this->milliseconds->convert(1000 * 1000, TimeUnit::Nanoseconds()));
    }

    public function testEquals(): void
    {
        self::assertTrue($this->milliseconds->equals(TimeUnit::Milliseconds()));
        self::assertFalse($this->milliseconds->equals(TimeUnit::Minutes()));
    }

    protected function setUp(): void
    {
        $this->milliseconds = TimeUnit::Milliseconds();
    }
}
