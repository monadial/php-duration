<?php

declare(strict_types=1);

namespace TMihalicka\Duration\TimeUnit;

/**
 * Time unit representing one second.
 *
 * @psalm-immutable
 */
final class Seconds extends TimeUnit
{
    public function __construct()
    {
        parent::__construct(self::SECOND_SCALE);
    }

    public static function make(): Seconds
    {
        return new static();
    }
}
