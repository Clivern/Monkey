<?php

/*
 * This file is part of Monkey - Apache CloudStack SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\API;

use Clivern\Monkey\API\Factory;
use PHPUnit\Framework\TestCase;

/**
 * Factory Class Test.
 *
 * @since 1.0.0
 */
class FactoryTest extends TestCase
{
    public function testAll()
    {
        $this->assertSame("Clivern\Monkey\API\Job", \get_class(Factory::job()));
        $this->assertSame("Clivern\Monkey\API\Caller", \get_class(Factory::caller()));
    }
}
