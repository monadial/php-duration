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
        self::assertEquals(1, $this->nanoseconds->toNanos(TimeUnit::NANO_SCALE));
    }

    public function testToMicros(): void
    {
        self::assertEquals(1, $this->nanoseconds->toMicros(TimeUnit::MICRO_SCALE));
    }

    public function testToMillis(): void
    {
        self::assertEquals(1, $this->nanoseconds->toMillis(TimeUnit::MILLI_SCALE));
    }

    public function testToSeconds(): void
    {
        self::assertEquals(1, $this->nanoseconds->toSeconds(TimeUnit::SECOND_SCALE));
    }

    public function testToMinutes(): void
    {
        self::assertEquals(1, $this->nanoseconds->toMinutes(TimeUnit::MINUTE_SCALE));
    }

    public function testToHours(): void
    {
        self::assertEquals(1, $this->nanoseconds->toHours(TimeUnit::HOUR_SCALE));
    }

    public function testToDays(): void
    {
        self::assertEquals(1, $this->nanoseconds->toDays(TimeUnit::DAY_SCALE));
    }

    public function testConvert(): void
    {
        self::assertEquals(TimeUnit::NANO_SCALE, $this->nanoseconds->convert(1, TimeUnit::Nanoseconds()));
        self::assertEquals(TimeUnit::MICRO_SCALE, $this->nanoseconds->convert(1, TimeUnit::Microseconds()));
        self::assertEquals(TimeUnit::MILLI_SCALE, $this->nanoseconds->convert(1, TimeUnit::Milliseconds()));
        self::assertEquals(TimeUnit::SECOND_SCALE, $this->nanoseconds->convert(1, TimeUnit::Seconds()));
        self::assertEquals(TimeUnit::MINUTE_SCALE, $this->nanoseconds->convert(1, TimeUnit::Minutes()));
        self::assertEquals(TimeUnit::HOUR_SCALE, $this->nanoseconds->convert(1, TimeUnit::Hours()));
        self::assertEquals(TimeUnit::DAY_SCALE, $this->nanoseconds->convert(1, TimeUnit::Days()));
    }

    protected function setUp(): void
    {
        $this->nanoseconds = TimeUnit::Nanoseconds();
    }
}
