<?php

/*
 * This file is part of Monkey - Apache CloudStack SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\API;

use Clivern\Monkey\API\CallerStatus;
use PHPUnit\Framework\TestCase;

/**
 * CallerStatus Class Test.
 *
 * @since 1.0.0
 */
class CallerStatusTest extends TestCase
{
    public function testConstants()
    {
        $this->assertSame("Clivern\Monkey\API\CallerStatus", \get_class(new CallerStatus()));
        $this->assertSame(CallerStatus::$PENDING, 'PENDING');
        $this->assertSame(CallerStatus::$IN_PROGRESS, 'IN_PROGRESS');
        $this->assertSame(CallerStatus::$ASYNC_JOB, 'ASYNC_JOB');
        $this->assertSame(CallerStatus::$FAILED, 'FAILED');
        $this->assertSame(CallerStatus::$SUCCEEDED, 'SUCCEEDED');
    }
}
