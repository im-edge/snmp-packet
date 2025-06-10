<?php

namespace IMEdge\Tests\SnmpPacket\Usm;

use IMEdge\SnmpPacket\Usm\SnmpPrivProtocol;
use IMEdge\SnmpPacket\Usm\PrivacyModule;
use IMEdge\SnmpPacket\Usm\RemoteEngine;
use IMEdge\SnmpPacket\Usm\SnmpAuthProtocol;
use IMEdge\Tests\SnmpPacket\TestCase;
use IMEdge\SnmpPacket\Util\TestHelper;

class PrivacyModuleTest extends TestCase
{
    public function testDecryptMd5DesSamplePacket(): void
    {
        $this->requirePrivacyProtocol(SnmpPrivProtocol::DES);
        $module = new PrivacyModule(
            'notsecure1',
            new RemoteEngine(TestHelper::unHex('80 00 1f 88 80 1e ce 80 47 02 bb 09 64 00 00 00 00')),
            SnmpAuthProtocol::MD5,
            SnmpPrivProtocol::DES,
        );
        $salt = TestHelper::unHex('00 00 00 01 35 33 b2 32');
        $encrypted = TestHelper::unHex(
            '00 2b bc 00 92 5b 1f 95 89 a2 37 e6 38 36 5f 9f b0 e5 81 1f 9e 2a 2c 89 3e 8b a6 43 99 78 32 ba'
            . ' 8f 0c 1d ee ac 5d e4 2f 69 1b 00 29 fb 5f 1f 47 af f3 c7 c4 ac b3 be 87'
        );
        $decrypted = TestHelper::unHex(
            '30 33 04 11 80 00 1f 88 80 1e ce 80 47 02 bb 09 64 00 00 00 00 04 00 a0 1c 02 04 57 cf 1c c8 02'
            . ' 01 00 02 01 00 30 0e 30 0c 06 08 2b 06 01 02 01 01 05 00 05 00 03 03 03'
        );
        $this->assertEqualsHex($decrypted, $module->decrypt($encrypted, $salt));
    }
}
