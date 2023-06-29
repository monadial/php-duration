<?php

declare(strict_types=1);

namespace Monadial\Duration\Tests;

use Monadial\Duration\FiniteDuration;
use Monadial\Duration\TimeUnit\Days;
use Monadial\Duration\TimeUnit\Hours;
use Monadial\Duration\TimeUnit\Microseconds;
use Monadial\Duration\TimeUnit\Milliseconds;
use Monadial\Duration\TimeUnit\Minutes;
use Monadial\Duration\TimeUnit\Nanoseconds;
use Monadial\Duration\TimeUnit\Seconds;
use Monadial\Duration\TimeUnit\TimeUnit;
use PHPUnit\Framework\TestCase;
final class FiniteDurationTest extends TestCase
{
    /**
     * @dataProvider fromNanosData
     */
    public function testFromNanos(int $nanos, FiniteDuration $duration): void
    {
        $createdDuration = FiniteDuration::fromNanos($nanos);
        self::assertTrue($createdDuration->equals($duration));
    }

    public function fromNanosData(): array
    {
        return [
            [1, FiniteDuration::fromTimeUnit(1, TimeUnit::Nanoseconds())],
            [1000, FiniteDuration::fromTimeUnit(1, TimeUnit::Microseconds())],
            [1000 * 1000, FiniteDuration::fromTimeUnit(1, TimeUnit::Milliseconds())],
            [1000 * 1000 * 1000, FiniteDuration::fromTimeUnit(1, TimeUnit::Seconds())],
            [1000 * 1000 * 1000, FiniteDuration::fromTimeUnit(1, TimeUnit::Seconds())],
            [1000 * 1000 * 1000 * 60, FiniteDuration::fromTimeUnit(1, TimeUnit::Minutes())],
            [1000 * 1000 * 1000 * 60 * 60, FiniteDuration::fromTimeUnit(1, TimeUnit::Hours())],
            [1000 * 1000 * 1000 * 60 * 60 * 24, FiniteDuration::fromTimeUnit(1, TimeUnit::Days())],
        ];
    }

    /**
     * @dataProvider fromStringData
     */
    public function testParseString(string $durationString, FiniteDuration $duration): void
    {
        $finiteDuration = FiniteDuration::fromString($durationString);
        self::assertTrue($finiteDuration->equals($duration));
    }

    public function testConversion(): void
    {
        $day = FiniteDuration::fromString('1 day');
        self::assertEquals(1, $day->toDays());
        self::assertEquals(24, $day->toHours());
        self::assertEquals(24 * 60, $day->toMinutes());
        self::assertEquals(24 * 60 * 60, $day->toSeconds());
        self::assertEquals(24 * 60 * 60 * 1000, $day->toMillis());
        self::assertEquals(24 * 60 * 60 * 1000 * 1000, $day->toMicros());
        self::assertEquals(24 * 60 * 60 * 1000 * 1000 * 1000, $day->toNanos());
    }

    public function testAdd(): void
    {
        $days = FiniteDuration::fromTimeUnit(1, TimeUnit::Days());
        $hours = FiniteDuration::fromTimeUnit(48, TimeUnit::Hours());
        self::assertTrue($days->add($hours)->equals(FiniteDuration::fromTimeUnit(3, TimeUnit::Days())));
    }

    public function testSubtract(): void
    {
        $hours = FiniteDuration::fromTimeUnit(96, TimeUnit::Hours());
        $days = FiniteDuration::fromTimeUnit(2, TimeUnit::Days());
        self::assertTrue($hours->subtract($days)->equals(FiniteDuration::fromTimeUnit(2, TimeUnit::Days())));
    }

    public function testMultiply(): void
    {
        $hours = FiniteDuration::fromTimeUnit(24, TimeUnit::Hours());
        self::assertTrue($hours->multiply(2)->equals(FiniteDuration::fromTimeUnit(48, TimeUnit::Hours())));
    }

    public function testDivide(): void
    {
        $hours = FiniteDuration::fromTimeUnit(48, TimeUnit::Hours());
        self::assertTrue($hours->division(2)->equals(FiniteDuration::fromTimeUnit(24, TimeUnit::Hours())));
    }

    public function testToUnit(): void
    {
        $hours = FiniteDuration::fromTimeUnit(48, TimeUnit::Hours());
        self::assertTrue($hours->toUnit(TimeUnit::Days())->equals(FiniteDuration::fromTimeUnit(2, TimeUnit::Days())));
    }

    public function testFactor(): void
    {
        $hours = FiniteDuration::fromTimeUnit(48, TimeUnit::Hours());
        self::assertTrue($hours->factor(2)->equals(FiniteDuration::fromTimeUnit(4, TimeUnit::Days())));
    }

    public function testUnary(): void
    {
        $hours = FiniteDuration::fromTimeUnit(48, TimeUnit::Hours());
        self::assertTrue($hours->unary()->equals(FiniteDuration::fromTimeUnit(-48, TimeUnit::Hours())));
    }

    public function fromStringData(): array
    {
        return [
            ['1 day', FiniteDuration::fromTimeUnit(1, Days::make())],
            ['1 hour', FiniteDuration::fromTimeUnit(1, Hours::make())],
            ['1 minute', FiniteDuration::fromTimeUnit(1, Minutes::make())],
            ['1 second', FiniteDuration::fromTimeUnit(1, Seconds::make())],
            ['1 millisecond', FiniteDuration::fromTimeUnit(1, Milliseconds::make())],
            ['1 microsecond', FiniteDuration::fromTimeUnit(1, Microseconds::make())],
            ['1 nanosecond', FiniteDuration::fromTimeUnit(1, Nanoseconds::make())],
            ['31 d', FiniteDuration::fromTimeUnit(31, Days::make())],
            ['24 h', FiniteDuration::fromTimeUnit(24, Hours::make())],
            ['60 min', FiniteDuration::fromTimeUnit(60, Minutes::make())],
            ['60 s', FiniteDuration::fromTimeUnit(60, Seconds::make())],
            ['1000 ms', FiniteDuration::fromTimeUnit(1000, Milliseconds::make())],
            ['1000 µs', FiniteDuration::fromTimeUnit(1000, Microseconds::make())],
            ['1000 ns', FiniteDuration::fromTimeUnit(1000, Nanoseconds::make())],
            ['30 sec', FiniteDuration::fromTimeUnit(30, Seconds::make())],
            ['100 millis', FiniteDuration::fromTimeUnit(100, Milliseconds::make())],
            ['200 micros', FiniteDuration::fromTimeUnit(200, Microseconds::make())],
            ['300 nanos', FiniteDuration::fromTimeUnit(300, Nanoseconds::make())],
        ];
    }
}
