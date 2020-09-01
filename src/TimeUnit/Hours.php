<?php

declare(strict_types=1);

namespace TMihalicka\Duration\TimeUnit;

/**
 * Time unit representing sixty minutes.
 *
 * @psalm-immutable
 */
final class Hours extends TimeUnit
{
    final public function __construct()
    {
        parent::__construct(self::HOUR_SCALE);
    }

    public static function make(): Hours
    {
        return new static();
    }
}
