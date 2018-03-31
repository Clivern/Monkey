<?php
namespace Tests\API;

use Clivern\Monkey\API\Factory;
use PHPUnit\Framework\TestCase;


/**
 * Factory Class Test
 *
 * @since 1.0.0
 * @package Tests\API
 */
class FactoryTest extends TestCase {

    public function testAll()
    {
        $this->assertEquals("Clivern\Monkey\API\Job", get_class(Factory::job()));
        $this->assertEquals("Clivern\Monkey\API\Caller", get_class(Factory::caller()));
    }
}