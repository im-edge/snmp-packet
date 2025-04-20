<?php

namespace IMEdge\Tests\Snmp;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function assertEqualsHex(string $expected, mixed $actual, string $message = ''): void
    {
        $this->assertEquals(
            TestHelper::niceHex($expected),
            TestHelper::niceHex($actual),
            $message
        );
    }
}
