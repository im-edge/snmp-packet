<?php

namespace IMEdge\Tests\SnmpPacket\VarBindValue;

use FreeDSx\Asn1\Encoder\BerEncoder;
use IMEdge\SnmpPacket\VarBindValue\ObjectIdentifier;
use IMEdge\SnmpPacket\VarBindValue\Value;
use IMEdge\SnmpPacket\Util\TestHelper;
use PHPUnit\Framework\TestCase;

class ObjectIdentifierTest extends TestCase
{
    public function testParseAndRenderOid(): void
    {
        $binary = TestHelper::unHex('06 06 2b 06 01 02 01 32');
        $encoder = new BerEncoder();
        $decoded = Value::fromAsn1($encoder->decode($binary));
        $this->assertInstanceOf(ObjectIdentifier::class, $decoded);
        $this->assertEquals('1.3.6.1.2.1.50', $decoded->getReadableValue());
        $this->assertEquals($binary, $encoder->encode($decoded->toASN1()));
    }
}
