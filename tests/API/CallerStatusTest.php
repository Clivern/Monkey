<?php
namespace Tests\API;

use Clivern\Monkey\API\CallerStatus;
use PHPUnit\Framework\TestCase;

/**
 * CallerStatus Class Test
 *
 * @since 1.0.0
 * @package Tests\API
 */
class CallerStatusTest extends TestCase {

    public function testConstants()
    {
        $this->assertEquals("Clivern\Monkey\API\CallerStatus", get_class(new CallerStatus()));
        $this->assertEquals(CallerStatus::$PENDING, "PENDING");
        $this->assertEquals(CallerStatus::$IN_PROGRESS, "IN_PROGRESS");
        $this->assertEquals(CallerStatus::$ASYNC_JOB, "ASYNC_JOB");
        $this->assertEquals(CallerStatus::$FAILED, "FAILED");
        $this->assertEquals(CallerStatus::$SUCCEEDED, "SUCCEEDED");
    }
}