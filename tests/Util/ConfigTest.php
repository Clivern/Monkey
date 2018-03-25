<?php
namespace Tests\Util;

use Clivern\Monkey\Util\Config;
use PHPUnit\Framework\TestCase;

/**
 * Config Class Test
 *
 * @since 1.0.0
 * @package Tests\Util
 */
class ConfigTest extends TestCase {

    /**
     * Test All Config Class Methods
     */
    public function testAll()
    {
        $config = new Config();
        $result01 = $config->addCloudStackServer("cs_us_01", [
            "api_key" => "12345678"
        ]);
        $result02 = $config->addCloudStackServer("cs_us_02", [
            "api_key" => "12345679"
        ]);
        $result03 = $config->addCloudStackServer("cs_us_03", [
            "api_key" => "12345677"
        ]);

        $this->assertEquals("Clivern\Monkey\Util\Config", get_class($config));
        $this->assertEquals("Clivern\Monkey\Util\Config", get_class($result01));
        $this->assertEquals("Clivern\Monkey\Util\Config", get_class($result02));
        $this->assertEquals("Clivern\Monkey\Util\Config", get_class($result03));
        $this->assertEquals($config->getCloudStackServers(), [
            "cs_us_01" => ["api_key" => "12345678"],
            "cs_us_02" => ["api_key" => "12345679"],
            "cs_us_03" => ["api_key" => "12345677"]
        ]);
        $this->assertTrue($config->isCloudStackServerExists("cs_us_01"));
        $this->assertTrue($config->removeCloudStackServer("cs_us_01"));
        $this->assertFalse($config->isCloudStackServerExists("cs_us_01"));
        $this->assertEquals($config->getCloudStackServer("cs_us_02"), ["api_key" => "12345679"]);
    }
}