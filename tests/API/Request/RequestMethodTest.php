<?php
namespace Tests\API\Request;

use PHPUnit\Framework\TestCase;
use Clivern\CloudStackMonkey\API\Request\RequestMethod;

/**
 * Request Method Class Test
 *
 * @since 1.0.0
 * @package Tests\API\Request
 */
class RequestMethodTest extends TestCase {

    public function testClass()
    {
        $this->assertEquals("Clivern\CloudStackMonkey\API\Request\RequestMethod", get_class(new RequestMethod()));
        $this->assertEquals("GET", RequestMethod::$GET);
        $this->assertEquals("POST", RequestMethod::$POST);
        $this->assertEquals("PUT", RequestMethod::$PUT);
        $this->assertEquals("DELETE", RequestMethod::$DELETE);
    }
}