<?php

namespace IMEdge\Tests\Snmp\VarBindValue;

use FreeDSx\Asn1\Encoder\BerEncoder;
use IMEdge\Snmp\Message\VarBind;
use IMEdge\Snmp\VarBindValue\ObjectIdentifier;
use IMEdge\Snmp\VarBindValue\OctetString;
use IMEdge\Tests\Snmp\TestCase;
use IMEdge\Tests\Snmp\TestHelper;

class VarBindValueTest extends TestCase
{
    protected ?BerEncoder $encoder = null;

    public function testSimpleVarBindWithOctetStringCanBeParsed(): void
    {
        $binary = TestHelper::unHex(
            '30 1d 06 08 2b 06 01 02 01 01 05 00 04 11 73 6e 6d 70 2d 62 6f 6f 6b 77 6f 72 6d 2e 6c 78 64'
        );
        $varBind = VarBind::fromAsn1($this->decodeSequence($binary));
        $this->assertEquals('1.3.6.1.2.1.1.5.0', $varBind->oid);
        $this->assertInstanceOf(OctetString::class, $varBind->value);
        $this->assertEquals('snmp-bookworm.lxd', $varBind->value->getReadableValue());
    }

    public function testSimpleVarBindWithObjectIdentifierCanBeParsed(): void
    {
        $binary = TestHelper::unHex('30 16 06 08 2b 06 01 02 01 01 02 00 06 0a 2b 06 01 04 01 bf 08 03 02 0a');
        $varBind = VarBind::fromAsn1($this->decodeSequence($binary));
        $this->assertEquals('1.3.6.1.2.1.1.2.0', $varBind->oid);
        $this->assertInstanceOf(ObjectIdentifier::class, $varBind->value);
        $this->assertEquals('1.3.6.1.4.1.8072.3.2.10', $varBind->value->getReadableValue());
    }
}
