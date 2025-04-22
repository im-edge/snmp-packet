<?php

namespace IMEdge\Tests\Snmp\Usm;

use IMEdge\Snmp\Usm\SnmpPrivProtocol;
use IMEdge\Snmp\Usm\PrivacyKey;
use IMEdge\Snmp\Usm\SnmpAuthProtocol;
use IMEdge\Tests\Snmp\TestHelper;
use PHPUnit\Framework\TestCase;

class PrivacyKeyTest extends TestCase
{
    /**
     * RFC 3414 A.3.1
     */
    public function testDigestAndDesKeyWithMd5(): void
    {
        $this->checkDigestAndKey(
            SnmpAuthProtocol::MD5,
            SnmpPrivProtocol::DES,
            '9f af 32 83 88 4e 92 83 4e bc 98 47 d8 ed d9 63',
            '52 6f 5e ed 9f cc e2 6f 89 64 c2 93 07 87 d8 2b'
        );
    }

    /**
     * RFC 3414 A.3.2
     */
    public function testDigestAndDesKeyWithSha(): void
    {
        $this->checkDigestAndKey(
            SnmpAuthProtocol::SHA1,
            SnmpPrivProtocol::DES,
            '9f b5 cc 03 81 49 7b 37 93 52 89 39 ff 78 8d 5d 79 14 52 11',
            '66 95 fe bc 92 88 e3 62 82 23 5f c7 15 1f 12 84'
            // We strip it to 16 bytes, example also has this:
            // . ' 97 b3 8f 3f'
        );
    }

    /**
     * draft-reeder-snmpv3-usm-3desede-00
     * B.1 Password-to-Key Chaining Sample Results using MD5
     */
    public function testDigestAndTripleDesKeyWithMd5(): void
    {
        $this->checkKey(
            SnmpAuthProtocol::MD5,
            SnmpPrivProtocol::TRIPLE_DES,
            '52 6f 5e ed 9f cc e2 6f 89 64 c2 93 07 87 d8 2b 79 ef f4 4a 90 65 0e e0 a3 a4 0a bf ac 5a cc 12'
        );
    }

    /**
     * draft-reeder-snmpv3-usm-3desede-00
     * B.2.  Password-to-Key Chaining Sample Results using SHA
     */
    public function testDigestAndTripleDesKeyWithSha(): void
    {
        $this->checkKey(
            SnmpAuthProtocol::SHA1,
            SnmpPrivProtocol::TRIPLE_DES,
            '66 95 fe bc 92 88 e3 62 82 23 5f c7 15 1f 12 84 97 b3 8f 3f 9b 8b 6d 78 93 6b a6 e7 d1 9d fd 9c'
            // Stripped, as we directly apply localization:
            // . ' d2 d5 06 55 47 74 3f b5'
        );
    }

    /**
     * draft-blumenthal-aes-usm-04
     * A.1. Sample Results of Extension of Localized Keys (e.g. usmAesCfb256PrivProtocol)
     */
    public function testAes256WithSha(): void
    {
        $this->checkKey(
            SnmpAuthProtocol::SHA1,
            SnmpPrivProtocol::AES256,
            '66 95 fe bc 92 88 e3 62 82 23 5f c7 15 1f 12 84 97 b3 8f 3f 50 5e 07 eb 9a f2 55 68 fa 1f 5d be'
        );
    }

    protected function checkKey(
        SnmpAuthProtocol $protocol,
        SnmpPrivProtocol $privProtocol,
        string $expected
    ): void {
        $pass = 'maplesyrup';
        $engineId = TestHelper::unHex('00 00 00 00 00 00 00 00 00 00 00 02');
        $key = PrivacyKey::generate($protocol, $privProtocol, $pass, $engineId);

        $this->assertEquals(
            $expected,
            TestHelper::niceHex($key)
        );
    }

    protected function checkDigestAndKey(
        SnmpAuthProtocol $protocol,
        SnmpPrivProtocol $privProtocol,
        string $digest,
        string $expected
    ): void {
        $pass = 'maplesyrup';
        $engineId = TestHelper::unHex('00 00 00 00 00 00 00 00 00 00 00 02');

        $this->assertEquals($digest, TestHelper::niceHex(PrivacyKey::digest($protocol, $pass)));
        $this->assertEquals(
            $expected,
            TestHelper::niceHex(PrivacyKey::generate($protocol, $privProtocol, $pass, $engineId))
        );
    }
}
