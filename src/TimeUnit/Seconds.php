<?php

declare(strict_types=1);

namespace TMihalicka\Duration\TimeUnit;

/**
 * Time unit representing one second.
 */
final class Seconds extends TimeUnit
{
    public function __construct()
    {
        parent::__construct(self::SECOND_SCALE);
    }

    protected static function make(): Seconds
    {
        return new static();
    }
}
