<?php

declare(strict_types=1);

/*
 * @author tmihalicka
 * @copyright PIXEL FEDERATION
 * @license Internal use only
 */

namespace TMihalicka\Duration\Tests\TimeUnit;

use PHPUnit\Framework\TestCase;
use TMihalicka\Duration\TimeUnit\Seconds;
use TMihalicka\Duration\TimeUnit\TimeUnit;

/**
 * @v
 */
class SecondsTest extends TestCase
{
    private Seconds $unit;

    public function testToDays(): void
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

    protected function setUp(): void
    {
        $this->unit = TimeUnit::Seconds();
    }
}
