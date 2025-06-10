<?php

namespace IMEdge\Tests\SnmpPacket;

use FreeDSx\Asn1\Encoder\BerEncoder;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\SequenceType;
use IMEdge\SnmpPacket\Usm\SnmpPrivProtocol;
use IMEdge\SnmpPacket\Util\TestHelper;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected ?BerEncoder $encoder = null;

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

    protected function decodeSequence(string $binary): SequenceType
    {
        $sequence = $this->decode($binary);
        assert($sequence instanceof SequenceType);

        return $sequence;
    }

    protected function decode(string $binary): AbstractType
    {
        $this->encoder ??= new BerEncoder();
        return $this->encoder->decode($binary);
    }

    protected function encode(AbstractType $asn1): string
    {
        $this->encoder ??= new BerEncoder();
        return $this->encoder->encode($asn1);
    }
}
