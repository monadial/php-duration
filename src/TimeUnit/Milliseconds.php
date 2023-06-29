<?php

declare(strict_types=1);

namespace Monadial\Duration\TimeUnit;

/**
 * Time unit representing one thousandth of a second.
 */
final class Milliseconds extends TimeUnit
{
    final public function __construct()
    {
        parent::__construct(self::MILLI_SCALE);
    }

    public static function make(): Milliseconds
    {
        return new Milliseconds();
    }
}
