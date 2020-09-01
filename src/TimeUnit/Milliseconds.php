<?php

declare(strict_types=1);

namespace TMihalicka\Duration\TimeUnit;

/**
 * Time unit representing one thousandth of a second.
 *
 * @psalm-immutable
 */
final class Milliseconds extends TimeUnit
{
    final public function __construct()
    {
        parent::__construct(self::MILLI_SCALE);
    }

    public static function make(): Milliseconds
    {
        return new static();
    }
}
