<?php
namespace Tests\API\Request;

use PHPUnit\Framework\TestCase;
use Clivern\CloudStackMonkey\API\Request\RequestType;

/**
 * Request Type Class Test
 *
 * @since 1.0.0
 * @package Tests\API\Request
 */
class RequestTypeTest extends TestCase {

    public function testClass()
    {
        $this->assertEquals("Clivern\CloudStackMonkey\API\Request\RequestType", get_class(new RequestType()));
        $this->assertEquals("ASYNCHRONOUS", RequestType::$ASYNCHRONOUS);
        $this->assertEquals("SYNCHRONOUS", RequestType::$SYNCHRONOUS);
    }
}