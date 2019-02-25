<?php

/*
 * This file is part of Monkey - Apache CloudStack SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\API\Request;

use Clivern\Monkey\API\Request\RequestMethod;
use PHPUnit\Framework\TestCase;

/**
 * Request Method Class Test.
 *
 * @since 1.0.0
 */
class RequestMethodTest extends TestCase
{
    public function testClass()
    {
        $this->assertSame("Clivern\Monkey\API\Request\RequestMethod", \get_class(new RequestMethod()));
        $this->assertSame('GET', RequestMethod::$GET);
        $this->assertSame('POST', RequestMethod::$POST);
        $this->assertSame('PUT', RequestMethod::$PUT);
        $this->assertSame('DELETE', RequestMethod::$DELETE);
    }
}
