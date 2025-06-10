<?php

namespace IMEdge\Tests\SnmpPacket\VarBindValue;

use FreeDSx\Asn1\Encoder\BerEncoder;
use IMEdge\SnmpPacket\VarBindValue\Integer32;
use IMEdge\SnmpPacket\VarBindValue\Value;
use IMEdge\SnmpPacket\Util\TestHelper;
use PHPUnit\Framework\TestCase;

class Integer32Test extends TestCase
{
    public function testParseAndRenderInteger(): void
    {
        $binary = TestHelper::unHex('02 01 48');
        $encoder = new BerEncoder();
        $decoded = Value::fromAsn1($encoder->decode($binary));
        $this->assertInstanceOf(Integer32::class, $decoded);
        $this->assertEquals(72, $decoded->getReadableValue());
        $this->assertEquals($binary, $encoder->encode($decoded->toASN1()));
    }
}
