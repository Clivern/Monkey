<?php
namespace Tests\API;

use Clivern\CloudStackMonkey\API\Caller;
use PHPUnit\Framework\TestCase;

/**
 * Caller Class Test
 *
 * @since 1.0.0
 * @package Tests\API
 */
class CallerTest extends TestCase {

    public function testClass()
    {
        $this->assertequals("Clivern\CloudStackMonkey\API\Caller", get_class(new Caller()));
    }
}