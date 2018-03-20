<?php
namespace Tests\API;

use Clivern\Monkey\API\DumpType;
use PHPUnit\Framework\TestCase;

/**
 * DumpType Class Test
 *
 * @since 1.0.0
 * @package Tests\API
 */
class DumpTypeTest extends TestCase {

    public function testConstants()
    {
        $this->assertEquals("Clivern\Monkey\API\DumpType", get_class(new DumpType()));
        $this->assertEquals(DumpType::$ARRAY, "ARRAY");
        $this->assertEquals(DumpType::$JSON, "JSON");
    }
}