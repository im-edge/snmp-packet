<?php

namespace IMEdge\Tests\Snmp\VarBindValue;

use FreeDSx\Asn1\Encoder\BerEncoder;
use IMEdge\Snmp\VarBindValue\NullValue;
use IMEdge\Snmp\VarBindValue\Value;
use IMEdge\Tests\Snmp\TestHelper;
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
