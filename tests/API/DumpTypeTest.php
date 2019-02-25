<?php

/*
 * This file is part of Monkey - Apache CloudStack SDK
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\API;

use Clivern\Monkey\API\DumpType;
use PHPUnit\Framework\TestCase;

/**
 * DumpType Class Test.
 *
 * @since 1.0.0
 */
class DumpTypeTest extends TestCase
{
    public function testConstants()
    {
        $this->assertSame("Clivern\Monkey\API\DumpType", \get_class(new DumpType()));
        $this->assertSame(DumpType::$ARRAY, 'ARRAY');
        $this->assertSame(DumpType::$JSON, 'JSON');
    }
}
