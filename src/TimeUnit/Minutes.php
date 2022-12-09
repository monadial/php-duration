<?php

declare(strict_types=1);

namespace Monadial\Duration\TimeUnit;

/**
 * Time unit representing sixty seconds.
 */
final class Minutes extends TimeUnit
{
    public function __construct()
    {
        parent::__construct(self::MINUTE_SCALE);
    }

    public static function make(): Minutes
    {
        return new static();
    }
}
