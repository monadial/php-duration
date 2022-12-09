<?php

declare(strict_types=1);

namespace Monadial\Duration\Exception;

use Monadial\Duration\Duration;
use RuntimeException;

final class UnableToConvertToDateTime extends RuntimeException
{
    private const MESSAGE = 'Unable to convert: %s to DateTime';

    public function __construct(Duration $duration)
    {
        parent::__construct(sprintf(self::MESSAGE, (string) $duration));
    }
}
