<?php
namespace Tests\API;

use Clivern\Monkey\API\JobStatus;
use PHPUnit\Framework\TestCase;

/**
 * JobStatus Class Test
 *
 * @since 1.0.0
 * @package Tests\API
 */
class JobStatusTest extends TestCase {

    public function testConstants()
    {
        $this->assertEquals("Clivern\Monkey\API\JobStatus", get_class(new JobStatus()));
        $this->assertEquals(JobStatus::$PENDING, "PENDING");
        $this->assertEquals(JobStatus::$IN_PROGRESS, "IN_PROGRESS");
        $this->assertEquals(JobStatus::$FAILED, "FAILED");
        $this->assertEquals(JobStatus::$FINISHED, "FINISHED");
    }
}