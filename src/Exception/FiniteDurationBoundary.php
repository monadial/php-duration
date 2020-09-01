<?php

declare(strict_types=1);

namespace TMihalicka\Duration\Exception;

use RuntimeException;

final class FiniteDurationBoundary extends RuntimeException
{
    private const MESSAGE = 'Duration is limited to +-(2^63-1)ns (ca. 292 years)';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
