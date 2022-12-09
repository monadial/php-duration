<?php

declare(strict_types=1);

namespace Monadial\Duration\Exception;

use InvalidArgumentException;

final class NanosecondsAreNotConvertibleToDateTime extends InvalidArgumentException
{
    private const MESSAGE = 'Nanoseconds are not convertible to datetime.';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
