<?php
namespace Tests\API\Request;

use PHPUnit\Framework\TestCase;
use Clivern\CloudStackMonkey\API\Request\ContentType;

/**
 * Content Type Class Test
 *
 * @since 1.0.0
 * @package Tests\API\Request
 */
class ContentTypeTest extends TestCase {

    public function testClass()
    {
        $this->assertEquals("Clivern\CloudStackMonkey\API\Request\ContentType", get_class(new ContentType()));
        $this->assertEquals("application/json", ContentType::$JSON);
    }
}