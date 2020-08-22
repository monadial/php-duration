<?php

declare(strict_types=1);

namespace TMihalicka\Duration\TimeUnit;

/**
 * Time unit representing one thousandth of a microsecond.
 */
final class Nanoseconds extends TimeUnit
{
    public function __construct()
    {
        parent::__construct(self::NANO_SCALE);
    }

    protected static function make(): Nanoseconds
    {
        return new static();
    }
}
