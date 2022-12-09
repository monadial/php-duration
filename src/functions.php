<?php

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */

declare(strict_types=1);

use Monadial\Duration\Deadline;
use Monadial\Duration\FiniteDuration;
use Monadial\Duration\TimeUnit\Days;
use Monadial\Duration\TimeUnit\Hours;
use Monadial\Duration\TimeUnit\Microseconds;
use Monadial\Duration\TimeUnit\Milliseconds;
use Monadial\Duration\TimeUnit\Minutes;
use Monadial\Duration\TimeUnit\Nanoseconds;
use Monadial\Duration\TimeUnit\Seconds;
use Monadial\Duration\TimeUnit\TimeUnit;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
if (function_exists('duration_from_string')) {
    function duration_from_string(string $duration): FiniteDuration
    {
        return FiniteDuration::fromString($duration);
    }
}

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
if (function_exists('duration_from_nanos')) {
    function duration_from_nanos(int $nanos): FiniteDuration
    {
        return FiniteDuration::fromNanos($nanos);
    }
}

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
if (function_exists('duration_from_time_unit')) {
    function duration_from_time_unit(int $length, TimeUnit $timeUnit): FiniteDuration
    {
        return FiniteDuration::fromTimeUnit($length, $timeUnit);
    }
}

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
if (function_exists('deadline')) {
    function deadline(FiniteDuration $duration): Deadline
    {
        return Deadline::create($duration);
    }
}

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
if (function_exists('nanoseconds')) {
    function nanoseconds(): Nanoseconds
    {
        return TimeUnit::Nanoseconds();
    }
}

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
if (function_exists('microseconds')) {
    function microseconds(): Microseconds
    {
        return TimeUnit::Microseconds();
    }
}

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
if (function_exists('milliseconds')) {
    function milliseconds(): Milliseconds
    {
        return TimeUnit::Milliseconds();
    }
}

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
if (function_exists('seconds')) {
    function seconds(): Seconds
    {
        return TimeUnit::Seconds();
    }
}

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
if (function_exists('minutes')) {
    function minutes(): Minutes
    {
        return TimeUnit::Minutes();
    }
}

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
if (function_exists('hours')) {
    function hours(): Hours
    {
        return TimeUnit::Hours();
    }
}

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
if (function_exists('days')) {
    function days(): Days
    {
        return TimeUnit::Days();
    }
}
