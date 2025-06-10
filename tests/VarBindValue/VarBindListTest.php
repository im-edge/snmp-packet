<?php

namespace IMEdge\Tests\SnmpPacket\VarBindValue;

use IMEdge\SnmpPacket\Message\VarBindList;
use IMEdge\SnmpPacket\VarBindValue\ObjectIdentifier;
use IMEdge\SnmpPacket\VarBindValue\OctetString;
use IMEdge\SnmpPacket\VarBindValue\TimeTicks;
use IMEdge\Tests\SnmpPacket\TestCase;
use IMEdge\SnmpPacket\Util\TestHelper;

class VarBindListTest extends TestCase
{
    public function testComplexVarBindListCanBeParsed(): void
    {
        $binary = TestHelper::unHex(<<<RAW
30 82 01 66 30 78 06 08 2b 06 01 02 01 01 01 00 04 6c 4c 69 6e 75 78 20 73 6e 6d 70 2d 62 6f 6f 6b 77 6f 72 6d 20 36 2e
38 2e 30 2d 35 37 2d 67 65 6e 65 72 69 63 20 23 35 39 7e 32 32 2e 30 34 2e 31 2d 55 62 75 6e 74 75 20 53 4d 50 20 50 52
45 45 4d 50 54 5f 44 59 4e 41 4d 49 43 20 57 65 64 20 4d 61 72 20 31 39 20 31 37 3a 30 37 3a 34 31 20 55 54 43 20 32 20
78 38 36 5f 36 34 30 16 06 08 2b 06 01 02 01 01 02 00 06 0a 2b 06 01 04 01 bf 08 03 02 0a 30 0e 06 08 2b 06 01 02 01 01
03 00 43 02 02 cd 30 23 06 08 2b 06 01 02 01 01 04 00 04 17 42 4f 46 48 20 3c 62 6f 66 68 40 65 78 61 6d 70 6c 65 2e 63
6f 6d 3e 30 27 06 08 2b 06 01 02 01 01 05 00 04 1b 69 6d 65 64 67 65 2d 73 6e 6d 70 2d 6c 61 62 2e 65 78 61 6d 70 6c 65
2e 63 6f 6d 30 24 06 08 2b 06 01 02 01 01 06 00 04 18 49 6e 20 74 68 65 20 6d 69 64 64 6c 65 20 6f 66 20 4e 6f 77 68 65
72 65 30 0d 06 08 2b 06 01 02 01 01 07 00 02 01 48 30 0d 06 08 2b 06 01 02 01 01 08 00 43 01 00 30 17 06 0a 2b 06 01 02
01 01 09 01 02 01 06 09 2b 06 01 06 03 0a 03 01 01 30 17 06 0a 2b 06 01 02 01 01 09 01 02 02 06 09 2b 06 01 06 03 0b 03
01 01
RAW
        );
        $varBinds = VarBindList::fromAsn1($this->decodeSequence($binary));
        $this->assertInstanceOf(OctetString::class, $varBinds->index(4)->value);
        $this->assertEquals('BOFH <bofh@example.com>', $varBinds->index(4)->value->getReadableValue());
        $this->assertInstanceOf(TimeTicks::class, $varBinds->index(3)->value);
        $this->assertInstanceOf(ObjectIdentifier::class, $varBinds->index(10)->value);
        $this->assertEquals('1.3.6.1.6.3.11.3.1.1', $varBinds->index(10)->value->getReadableValue());
        $this->assertEqualsHex($binary, $this->encode($varBinds->toAsn1()));
    }
}
