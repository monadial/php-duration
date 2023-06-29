<?php

declare(strict_types=1);

namespace Monadial\Duration;

use InvalidArgumentException;
use Monadial\Duration\TimeUnit\Days;
use Monadial\Duration\TimeUnit\Hours;
use Monadial\Duration\TimeUnit\Microseconds;
use Monadial\Duration\TimeUnit\Milliseconds;
use Monadial\Duration\TimeUnit\Minutes;
use Monadial\Duration\TimeUnit\Nanoseconds;
use Monadial\Duration\TimeUnit\Seconds;
use Monadial\Duration\TimeUnit\TimeUnit;

final class FiniteDurationStringParser
{
    private const DURATION_FORMAT = '/^\d+ [\wµ+]/';

    private const TIME_UNIT_LABELS = [
        Days::class => 'd day days',
        Hours::class => 'h hour hours',
        Minutes::class => 'min minute minutes',
        Seconds::class => 's sec second seconds',
        Milliseconds::class => 'ms millis millisecond milliseconds',
        Microseconds::class => 'us µs micro micros microsecond microseconds',
        Nanoseconds::class => 'ns nano nanos nanosecond nanoseconds',
    ];

    public static function parse(string $duration): FiniteDuration
    {
        $trimmed = trim($duration);
        self::validate($trimmed);

        [$length, $unit] = explode(" ", $trimmed);

        if (self::isValid($duration)) {
            throw new InvalidArgumentException(
                sprintf('Unable to parse given string `%s`, probably invalid unit!', $duration)
            );
        }

        /** @var class-string<TimeUnit> $unit */
        $unit = self::timeUnit()[$unit];

        return FiniteDuration::fromTimeUnit((int)$length, $unit::make());
    }

    public static function isValid(string $duration): bool
    {
        return array_key_exists(trim($duration), self::timeUnit());
    }

    public static function timeUnit(): array
    {
        $result = [];
        foreach (self::TIME_UNIT_LABELS as $unit => $labels) {
            $words = self::words($labels);
            foreach ($words as $word) {
                $result[$word] = $unit;
            }
        }

        return $result;
    }

    private static function words(string $input): array
    {
        return preg_split('/\s+/', $input);
    }

    private static function validate(string $duration): void
    {
        if (!preg_match(self::DURATION_FORMAT, $duration)) {
            throw new InvalidArgumentException(sprintf('Invalid input format, required %s', self::DURATION_FORMAT));
        }
    }
}
