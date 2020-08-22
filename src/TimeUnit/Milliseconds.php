<?php

declare(strict_types=1);

namespace TMihalicka\Duration\TimeUnit;

/**
 * Time unit representing one thousandth of a second.
 */
final class Milliseconds extends TimeUnit
{
    public function __construct()
    {
        parent::__construct(self::MILLI_SCALE);
    }

    protected static function make(): Milliseconds
    {
        return new static();
    }
}
