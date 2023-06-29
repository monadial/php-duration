<?php

declare(strict_types=1);

/*
 * @author tmihalicka
 * @copyright PIXEL FEDERATION
 * @license Internal use only
 */

namespace Monadial\Duration\Tests\TimeUnit;

use Monadial\Duration\TimeUnit\Nanoseconds;
use Monadial\Duration\TimeUnit\TimeUnit;
use PHPUnit\Framework\TestCase;

class NanosecondsTest extends TestCase
{
    private Nanoseconds $nanoseconds;

    public function testToNanos(): void
    {
        self::assertEquals(1, $this->nanoseconds->toNanos(1));
    }

    public function testToMicros(): void
    {
        self::assertEquals(1, $this->nanoseconds->toMicros(1000));
    }

    public function testToMillis(): void
    {
        self::assertEquals(1, $this->nanoseconds->toMillis(1000 * 1000));
    }

    public function testToSeconds(): void
    {
        self::assertEquals(1, $this->nanoseconds->toSeconds(1000 * 1000 * 1000));
    }

    public function testToMinutes(): void
    {
        self::assertEquals(1, $this->nanoseconds->toMinutes(1000 * 1000 * 1000 * 60));
    }

    public function testToHours(): void
    {
        self::assertEquals(1, $this->nanoseconds->toHours(1000 * 1000 * 1000 * 60 * 60));
    }

    public function testToDays(): void
    {
        self::assertEquals(1, $this->nanoseconds->toDays(1000 * 1000 * 1000 * 60 * 60 * 24));
    }


    public function testConversion(): void
    {
        self::assertEquals(24 * 60 * 60 * 1000 * 1000 * 1000, $this->nanoseconds->convert(1, TimeUnit::Days()));
        self::assertEquals(60 * 60 * 1000 * 1000 * 1000, $this->nanoseconds->convert(1, TimeUnit::Hours()));
        self::assertEquals(60 * 1000 * 1000 * 1000, $this->nanoseconds->convert(1, TimeUnit::Minutes()));
        self::assertEquals(1000 * 1000 * 1000, $this->nanoseconds->convert(1, TimeUnit::Seconds()));
        self::assertEquals(1000 * 1000, $this->nanoseconds->convert(1, TimeUnit::Milliseconds()));
        self::assertEquals(1000, $this->nanoseconds->convert(1, TimeUnit::Microseconds()));
        self::assertEquals(1, $this->nanoseconds->convert(1, TimeUnit::Nanoseconds()));
    }

    public function testEquals(): void
    {
        self::assertTrue($this->nanoseconds->equals(TimeUnit::Nanoseconds()));
        self::assertFalse($this->nanoseconds->equals(TimeUnit::Minutes()));
    }
    protected function setUp(): void
    {
        $this->nanoseconds = TimeUnit::Nanoseconds();
    }
}
