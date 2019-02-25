<?php

/*
 * This file is part of Monkey - Apache CloudStack SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\API\Request;

use Clivern\Monkey\API\Request\RequestType;
use PHPUnit\Framework\TestCase;

/**
 * Request Type Class Test.
 *
 * @since 1.0.0
 */
class RequestTypeTest extends TestCase
{
    public function testClass()
    {
        $this->assertSame("Clivern\Monkey\API\Request\RequestType", \get_class(new RequestType()));
        $this->assertSame('ASYNCHRONOUS', RequestType::$ASYNCHRONOUS);
        $this->assertSame('SYNCHRONOUS', RequestType::$SYNCHRONOUS);
    }
}
