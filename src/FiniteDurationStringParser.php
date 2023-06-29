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
    public const TIME_UNIT_LABELS = [
        Days::class => 'day d days',
        Hours::class => 'hour h hours',
        Microseconds::class => 'microsecond us µs micro micros microseconds',
        Milliseconds::class => 'millisecond ms millis milliseconds',
        Minutes::class => 'minute min minutes',
        Nanoseconds::class => 'nanosecond ns nano nanos nanoseconds',
        Seconds::class => 'second s sec seconds',
    ];

    private const DURATION_FORMAT = '/^\d+ [\wµ+]/';

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
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

        $unit = self::timeUnit()[$unit];

        return FiniteDuration::fromTimeUnit((int)$length, $unit::make());
    }

    public static function isValid(string $duration): bool
    {
        return array_key_exists(trim($duration), self::timeUnit());
    }

    /**
     * @return array<class-string<TimeUnit>, array<string>>
     */
    public static function timeUnits(): array
    {
        $result = [];
        foreach (self::TIME_UNIT_LABELS as $unit => $label) {
            $result[$unit] = self::words($label);
        }

        return $result;
    }

    /**
     * @return array<string, class-string<TimeUnit>>
     */
    private static function timeUnit(): array
    {
        $result = [];
        foreach (self::timeUnits() as $unit => $labels) {
            foreach ($labels as $label) {
                $result[$label] = $unit;
            }
        }

        return $result;
    }

    /**
     * @return array<string>
     */
    private static function words(string $input): array
    {
        $result = preg_split('/\s+/', $input);

        if ($result === false) {
            throw new InvalidArgumentException('Invalid input format, required /\s+/');
        }

        return $result;
    }

    private static function validate(string $duration): void
    {
        if (!preg_match(self::DURATION_FORMAT, $duration)) {
            throw new InvalidArgumentException(sprintf('Invalid input format, required %s', self::DURATION_FORMAT));
        }
    }
}
