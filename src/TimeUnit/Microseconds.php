<?php

declare(strict_types=1);

namespace TMihalicka\Duration\TimeUnit;

/**
 * Time unit representing one thousandth of a millisecond.
 */
final class Microseconds extends TimeUnit
{
    public function __construct()
    {
        parent::__construct(self::MICRO_SCALE);
    }

    protected static function make(): Microseconds
    {
        return new static();
    }
}
