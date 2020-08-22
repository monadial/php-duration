<?php

declare(strict_types=1);

namespace TMihalicka\Duration\TimeUnit;

/**
 * Time unit representing twenty four hours.
 */
final class Days extends TimeUnit
{
    public function __construct()
    {
        parent::__construct(self::DAY_SCALE);
    }

    protected static function make(): Days
    {
        return new static();
    }
}
