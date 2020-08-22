<?php

declare(strict_types=1);

/*
 * @author tmihalicka
 * @copyright PIXEL FEDERATION
 * @license Internal use only
 */

namespace TMihalicka\Duration\Tests\TimeUnit;

use TMihalicka\Duration\TimeUnit\Hours;
use PHPUnit\Framework\TestCase;
use TMihalicka\Duration\TimeUnit\TimeUnit;

class HoursTest extends TestCase
{
    private Hours $unit;

    protected function setUp(): void
    {
        $this->unit = TimeUnit::Hours();
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
