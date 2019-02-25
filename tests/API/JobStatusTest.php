<?php

/*
 * This file is part of Monkey - Apache CloudStack SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\API;

use Clivern\Monkey\API\JobStatus;
use PHPUnit\Framework\TestCase;

/**
 * JobStatus Class Test.
 *
 * @since 1.0.0
 */
class JobStatusTest extends TestCase
{
    public function testConstants()
    {
        $this->assertSame("Clivern\Monkey\API\JobStatus", \get_class(new JobStatus()));
        $this->assertSame(JobStatus::$PENDING, 'PENDING');
        $this->assertSame(JobStatus::$IN_PROGRESS, 'IN_PROGRESS');
        $this->assertSame(JobStatus::$FAILED, 'FAILED');
        $this->assertSame(JobStatus::$SUCCEEDED, 'SUCCEEDED');
    }
}
