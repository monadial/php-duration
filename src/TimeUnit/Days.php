<?php

declare(strict_types=1);

namespace Monadial\Duration\TimeUnit;

/**
 * Time unit representing twenty-four hours.
 */
final class Days extends TimeUnit
{
    final public function __construct()
    {
        parent::__construct(self::DAY_SCALE);
    }

    public static function make(): self
    {
        return new self();
    }
}
