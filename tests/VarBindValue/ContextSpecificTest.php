<?php

namespace IMEdge\Tests\SnmpPacket\VarBindValue;

use FreeDSx\Asn1\Encoder\BerEncoder;
use IMEdge\SnmpPacket\VarBindValue\ContextSpecific;
use IMEdge\SnmpPacket\VarBindValue\Value;
use IMEdge\SnmpPacket\Util\TestHelper;
use PHPUnit\Framework\TestCase;

class ContextSpecificTest extends TestCase
{
    public function testParseAndRenderNoSuchObject(): void
    {
        $binary = TestHelper::unHex('80 00');
        $encoder = new BerEncoder();
        $decoded = Value::fromAsn1($encoder->decode($binary));
        $this->assertInstanceOf(ContextSpecific::class, $decoded);
        $this->assertEquals('NO_SUCH_OBJECT', $decoded->value->name);
        $this->assertEquals($binary, $encoder->encode($decoded->toASN1()));
    }

    public function testParseAndRenderEndOfMib(): void
    {
        $binary = TestHelper::unHex('82 00');
        $encoder = new BerEncoder();
        $decoded = Value::fromAsn1($encoder->decode($binary));
        $this->assertInstanceOf(ContextSpecific::class, $decoded);
        $this->assertEquals('END_OF_MIB_VIEW', $decoded->value->name);
        $this->assertEquals($binary, $encoder->encode($decoded->toASN1()));
    }
}
