<?php

namespace IMEdge\Tests\SnmpPacket\VarBindValue;

use FreeDSx\Asn1\Encoder\BerEncoder;
use IMEdge\SnmpPacket\VarBindValue\NullValue;
use IMEdge\SnmpPacket\VarBindValue\Value;
use IMEdge\Tests\SnmpPacket\TestHelper;
use PHPUnit\Framework\TestCase;

class NullValueTest extends TestCase
{
    public function testParseAndRenderNull(): void
    {
        $binary = TestHelper::unHex('05 00');
        $encoder = new BerEncoder();
        $decoded = Value::fromAsn1($encoder->decode($binary));
        $this->assertInstanceOf(NullValue::class, $decoded);
        $this->assertEquals($binary, $encoder->encode($decoded->toASN1()));
    }
}
