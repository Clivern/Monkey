<?php

/*
 * This file is part of Monkey - Apache CloudStack SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\API\Request;

use Clivern\Monkey\API\Request\ResponseType;
use PHPUnit\Framework\TestCase;

/**
 * Response Type Class Test.
 *
 * @since 1.0.0
 */
class ResponseTypeTest extends TestCase
{
    public function testClass()
    {
        $this->assertSame("Clivern\Monkey\API\Request\ResponseType", \get_class(new ResponseType()));
        $this->assertSame('json', ResponseType::$JSON);
    }
}
