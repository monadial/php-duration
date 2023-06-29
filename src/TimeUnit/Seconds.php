<?php

declare(strict_types=1);

namespace Monadial\Duration\TimeUnit;

/**
 * Time unit representing one second.
 */
final class Seconds extends TimeUnit
{
    public function __construct()
    {
        parent::__construct(self::SECOND_SCALE);
    }

    public static function make(): Seconds
    {
        return new Seconds();
    }
}
