<?php

declare(strict_types=1);

namespace Monadial\Duration\TimeUnit;

/**
 * Time unit representing one thousandth of a microsecond.
 *
 * @psalm-immutable
 */
final class Nanoseconds extends TimeUnit
{
    public function __construct()
    {
        parent::__construct(self::NANO_SCALE);
    }

    public static function make(): Nanoseconds
    {
        return new static();
    }
}
