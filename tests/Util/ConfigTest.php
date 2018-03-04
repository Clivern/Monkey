<?php

namespace Tests\Util;

use Clivern\CloudStackMonkey\Util\Config;
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
		$result01 = $config->addCloudStackNode("cs_us_01", [
			"api_key" => "12345678"
		]);
		$result02 = $config->addCloudStackNode("cs_us_02", [
			"api_key" => "12345679"
		]);
		$result03 = $config->addCloudStackNode("cs_us_03", [
			"api_key" => "12345677"
		]);

		$this->assertEquals("Clivern\CloudStackMonkey\Util\Config", get_class($config));
		$this->assertEquals("Clivern\CloudStackMonkey\Util\Config", get_class($result01));
		$this->assertEquals("Clivern\CloudStackMonkey\Util\Config", get_class($result02));
		$this->assertEquals("Clivern\CloudStackMonkey\Util\Config", get_class($result03));
		$this->assertEquals($config->getCloudStackNodes(), [
			"cs_us_01" => ["api_key" => "12345678"],
			"cs_us_02" => ["api_key" => "12345679"],
			"cs_us_03" => ["api_key" => "12345677"]
		]);
		$this->assertTrue($config->isCloudStackNodeExists("cs_us_01"));
		$this->assertTrue($config->removeCloudStackNode("cs_us_01"));
		$this->assertFalse($config->isCloudStackNodeExists("cs_us_01"));
		$this->assertEquals($config->getCloudStackNode("cs_us_02"), ["api_key" => "12345679"]);
		$config->configLogging(["logging" => true]);
		$this->assertEquals($config->getLoggingConfigs(), ["logging" => true]);
    }
}