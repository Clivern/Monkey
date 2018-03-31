<?php
namespace Tests\API\Request;

use PHPUnit\Framework\TestCase;
use Clivern\Monkey\API\Request\ResponseType;

/**
 * Response Type Class Test
 *
 * @since 1.0.0
 * @package Tests\API\Request
 */
class ResponseTypeTest extends TestCase {

    public function testClass()
    {
        $this->assertEquals("Clivern\Monkey\API\Request\ResponseType", get_class(new ResponseType()));
        $this->assertEquals("json", ResponseType::$JSON);
    }
}