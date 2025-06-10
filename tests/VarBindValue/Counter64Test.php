<?php

namespace IMEdge\Tests\SnmpPacket\VarBindValue;

use FreeDSx\Asn1\Encoder\BerEncoder;
use IMEdge\SnmpPacket\VarBindValue\Counter64;
use IMEdge\SnmpPacket\VarBindValue\Value;
use PHPUnit\Framework\TestCase;

class Counter64Test extends TestCase
{
    public function testParseAndRenderSimpleCounter64(): void
    {
        $binary = "\x46\x03\x09\x48\xd2";
        $encoder = new BerEncoder();
        $decoded = Value::fromAsn1($encoder->decode($binary));
        $this->assertInstanceOf(Counter64::class, $decoded);
        $this->assertEquals('608466', $decoded->getReadableValue());
        $this->assertEquals($binary, $encoder->encode($decoded->toASN1()));
    }
}
