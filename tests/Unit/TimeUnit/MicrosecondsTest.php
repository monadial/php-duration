<?php

declare(strict_types=1);

/*
 * @author tmihalicka
 * @copyright PIXEL FEDERATION
 * @license Internal use only
 */

namespace Monadial\Duration\Tests\TimeUnit;

use Monadial\Duration\TimeUnit\Microseconds;
use Monadial\Duration\TimeUnit\TimeUnit;
use PHPUnit\Framework\TestCase;

class MicrosecondsTest extends TestCase
{
    private Microseconds $micros;

    public function testToNanos(): void
    {
        self::assertEquals(1000, $this->micros->toNanos(1));
    }

    public function testToMicros(): void
    {
        self::assertEquals(1, $this->micros->toMicros(1));
    }

    public function testToMillis(): void
    {
        self::assertEquals(1, $this->micros->toMillis(1000));
    }

    public function testToSeconds(): void
    {
        self::assertEquals(1, $this->micros->toSeconds(1000000));
    }

    public function testToMinutes(): void
    {
        self::assertEquals(1, $this->micros->toMinutes(1000000 * 60));
    }

    public function testToHours(): void
    {
        self::assertEquals(1, $this->micros->toHours(1000000 * 60 * 60));
    }

    public function testToDays(): void
    {
        self::assertEquals(1, $this->micros->toDays(1000000 * 60 * 60 * 24));
    }

    public function testConversion(): void
    {
        self::assertEquals(24 * 60 * 60 * 1000 * 1000, $this->micros->convert(1, TimeUnit::Days()));
        self::assertEquals(60 * 60 * 1000 * 1000, $this->micros->convert(1, TimeUnit::Hours()));
        self::assertEquals(60 * 1000 * 1000, $this->micros->convert(1, TimeUnit::Minutes()));
        self::assertEquals(1000 * 1000, $this->micros->convert(1, TimeUnit::Seconds()));
        self::assertEquals(1000, $this->micros->convert(1, TimeUnit::Milliseconds()));
        self::assertEquals(1, $this->micros->convert(1, TimeUnit::Microseconds()));
        self::assertEquals(1, $this->micros->convert(1000, TimeUnit::Nanoseconds()));
    }

    public function testEquals(): void
    {
        self::assertTrue($this->micros->equals(TimeUnit::Microseconds()));
        self::assertFalse($this->micros->equals(TimeUnit::Minutes()));
    }

    protected function setUp(): void
    {
        $this->micros = TimeUnit::Microseconds();
    }
}
