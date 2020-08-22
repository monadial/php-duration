<?php

declare(strict_types=1);

namespace TMihalicka\Duration\TimeUnit;

/**
 * Time unit representing sixty minutes.
 */
final class Hours extends TimeUnit
{
    public function __construct()
    {
        parent::__construct(self::HOUR_SCALE);
    }

    protected static function make(): Hours
    {
        return new static();
    }
}
