<?php

/*
 * This file is part of Monkey - Apache CloudStack SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\API\Request;

use Clivern\Monkey\API\Request\ContentType;
use PHPUnit\Framework\TestCase;

/**
 * Content Type Class Test.
 *
 * @since 1.0.0
 */
class ContentTypeTest extends TestCase
{
    public function testClass()
    {
        $this->assertSame("Clivern\Monkey\API\Request\ContentType", \get_class(new ContentType()));
        $this->assertSame('application/json', ContentType::$JSON);
    }
}
