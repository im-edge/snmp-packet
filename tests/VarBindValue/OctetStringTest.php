<?php

namespace IMEdge\Tests\SnmpPacket\VarBindValue;

use FreeDSx\Asn1\Encoder\BerEncoder;
use IMEdge\SnmpPacket\VarBindValue\OctetString;
use IMEdge\SnmpPacket\VarBindValue\Value;
use IMEdge\SnmpPacket\Util\TestHelper;
use PHPUnit\Framework\TestCase;

class OctetStringTest extends TestCase
{
    public function testParseAndRenderOctetString(): void
    {
        $binary = TestHelper::unHex(
            '04 22 54 68 65 20 4d 49 42 20 6d 6f 64 75 6c 65 20 66 6f 72 20 53 4e 4d 50 76 32 20 65 6e'
            . ' 74 69 74 69 65 73'
        );
        $encoder = new BerEncoder();
        $decoded = Value::fromAsn1($encoder->decode($binary));
        $this->assertInstanceOf(OctetString::class, $decoded);
        $this->assertEquals('The MIB module for SNMPv2 entities', $decoded->getReadableValue());
        $this->assertEquals($binary, $encoder->encode($decoded->toASN1()));
    }
}
