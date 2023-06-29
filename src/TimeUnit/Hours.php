<?php

declare(strict_types=1);

namespace Monadial\Duration\TimeUnit;

/**
 * Time unit representing sixty minutes.
 */
final class Hours extends TimeUnit
{
    final public function __construct()
    {
        parent::__construct(self::HOUR_SCALE);
    }

    public static function make(): self
    {
        return new self();
    }
}
