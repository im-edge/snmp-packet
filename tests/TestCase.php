<?php

namespace IMEdge\Tests\Snmp;

use IMEdge\Snmp\Usm\SnmpPrivProtocol;
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

    protected function requirePrivacyProtocol(SnmpPrivProtocol $protocol): void
    {
        if (! in_array($protocol->getOpenSslCipherAlgo(), openssl_get_cipher_methods())) {
            $this->markTestSkipped('This platform does not support the DES protocol');
        }

    }
}
