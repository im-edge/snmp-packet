<?php

namespace IMEdge\Tests\SnmpPacket\VarBindValue;

use FreeDSx\Asn1\Encoder\BerEncoder;
use IMEdge\SnmpPacket\VarBindValue\TimeTicks;
use IMEdge\SnmpPacket\VarBindValue\Value;
use PHPUnit\Framework\TestCase;

class TimeTickTest extends TestCase
{
    public function testParseAndRenderTimeTicks(): void
    {
        $binary = "\x43\x01\x04";
        $encoder = new BerEncoder();
        $decoded = Value::fromAsn1($encoder->decode($binary));
        $this->assertInstanceOf(TimeTicks::class, $decoded);
        $this->assertEquals(4, $decoded->getReadableValue());
        $this->assertEquals($binary, $encoder->encode($decoded->toASN1()));
    }
}
