<?php

namespace IMEdge\Tests\Snmp\VarBindValue;

use FreeDSx\Asn1\Encoder\BerEncoder;
use IMEdge\Snmp\VarBindValue\Integer32;
use IMEdge\Snmp\VarBindValue\Value;
use IMEdge\Tests\Snmp\TestHelper;
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
