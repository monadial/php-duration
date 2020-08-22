<?php

declare(strict_types=1);

namespace TMihalicka\Duration\TimeUnit;

/**
 * Time unit representing sixty seconds.
 */
final class Minutes extends TimeUnit
{
    public function __construct()
    {
        parent::__construct(self::MINUTE_SCALE);
    }

    protected static function make(): Minutes
    {
        return new static();
    }
}
