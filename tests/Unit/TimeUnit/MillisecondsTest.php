<?php

declare(strict_types=1);

/*
 * @author tmihalicka
 * @copyright PIXEL FEDERATION
 * @license Internal use only
 */

namespace Monadial\Duration\Tests\TimeUnit;

use PHPUnit\Framework\TestCase;
use Monadial\Duration\TimeUnit\Milliseconds;
use Monadial\Duration\TimeUnit\TimeUnit;

class MillisecondsTest extends TestCase
{
    private Milliseconds $unit;

    protected function setUp(): void
    {
        $this->unit = TimeUnit::Milliseconds();
    }

    public function testToDays(): void
    {

    }

    public function testConvert(): void
    {

    }

    public function testToSeconds(): void
    {

    }

    public function testToMicros(): void
    {

    }

    public function testToMinutes(): void
    {

    }

    public function testToHours(): void
    {

    }

    public function testToMillis(): void
    {

    }

    public function testToNanos(): void
    {

    }
}
