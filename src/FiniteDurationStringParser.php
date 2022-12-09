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

use function Functional\flat_map;
use function Functional\head;
use function Functional\last;
use function Functional\map;
use function Functional\tail;

/**
 * Parse string representation of duration
 */
final class FiniteDurationStringParser
{
    private const DURATION_FORMAT = '/^\d+ \w+/';

    /** @psalm-var array<class-string<TimeUnit>, string> */
    private const TIME_UNIT_LABELS = [
        Days::class => 'd day',
        Hours::class => 'h hour',
        Minutes::class => 'min minute',
        Seconds::class => 's sec second',
        Milliseconds::class => 'ms millis millisecond',
        Microseconds::class => 'µs micro microsecond',
        Nanoseconds::class => 'ns nano nanosecond',
    ];

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function parse(string $duration): FiniteDuration
    {
        $trimmed = trim($duration);
        self::validate($trimmed);

        [$length, $unit] = explode(" ", $trimmed);

        if (!array_key_exists($unit, self::timeUnit())) {
            throw new InvalidArgumentException(
                sprintf('Unable to parse given string %s, probably invalid unit!.', $duration),
            );
        }

        /** @psalm-var class-string<TimeUnit> $unitClass */
        $unitClass = self::timeUnit()[$unit];

        return FiniteDuration::fromTimeUnit((int)$length, $unitClass::make());
    }

    /**
     * @return array<string, string>
     * @psalm-return array<class-string<TimeUnit>, string>
     */
    public static function timeUnitName(): array
    {
        /** @psalm-var array<class-string<TimeUnit>, string> $timeUnitNames */
        $timeUnitNames = map(
            self::TIME_UNIT_LABELS,
            static function (string $word): string {
                /** @var string $last */
                $last = last(self::words($word));

                return $last;
            },
        );

        return $timeUnitNames;
    }

    /**
     * @return array<string>
     */
    private static function words(string $input): array
    {
        /** @var array<string> $explodedWords */
        $explodedWords = preg_split('/\s+/', $input);

        return $explodedWords;
    }

    /**
     * @param array<string> $labels
     * @return array<string>
     */
    private static function expandLabels(array $labels): array
    {
        /** @var array<string> $result */
        $result = array_merge(
            [head($labels)],
            flat_map(
                tail($labels),
                static fn (string $label): array => [$label, $label . 's'],
            ),
        );

        return $result;
    }

    /**
     * @psalm-return array<string, class-string<TimeUnit>>
     */
    private static function timeUnit(): array
    {
        /** @psalm-var array<array<string, class-string<TimeUnit>>> $result */
        $result = flat_map(
            self::TIME_UNIT_LABELS,
            static fn (string $names, string $unit) => map(
                self::expandLabels(self::words($names)),
                static fn (string $name) => [$name => $unit],
            ),
        );

        return array_merge(...$result);
    }

    private static function validate(string $duration): void
    {
        if (!preg_match(self::DURATION_FORMAT, $duration)) {
            throw new InvalidArgumentException(sprintf('Invalid input format, required %s', self::DURATION_FORMAT));
        }
    }
}
