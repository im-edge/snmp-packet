<?php

namespace IMEdge\Snmp\Usm;

enum SnmpPrivProtocol: string
{
    case DES        = 'des';     // RFC 3826
    case TRIPLE_DES = '3des';    // Cisco
    case AES        = 'aes128';  // Cisco, others
    case AES192     = 'aes192';  // draft-blumenthal-aes-usm-04
    case AES192C    = 'aes192c'; // Cisco, others -> draft-reeder-snmpv3-usm-3desede-00
    case AES256     = 'aes256';  // draft-blumenthal-aes-usm-04
    case AES256C    = 'aes256c'; // Cisco, others -> draft-reeder-snmpv3-usm-3desede-00

    /**
     * Length in Bytes
     */
    public function getKeyLength(): int
    {
        return match ($this) {
            self::DES        => 8,  // 64bit
            self::TRIPLE_DES => 24, // 192bit: Stronger, but slower
            self::AES        => 16, // 128 Bit: Standard for SNMPv3 since RFC 3826
            self::AES192, self::AES192C => 24, // 192 Bit: rarely used
            self::AES256, self::AES256C => 32, // 256 Bit: currently the most secure one
        };
    }

    /**
     * Longer than key length, might be used for salt
     */
    public function getRequiredKeyLength(): int
    {
        return match ($this) {
            self::DES        => 16,
            self::TRIPLE_DES => 32,

            // Still unsure, needs investigation:
            self::AES        => 16,
            self::AES192, self::AES192C => 24,
            self::AES256, self::AES256C => 32,
        };
    }

    public function getOpenSslCipherAlgo(): string
    {
        return match ($this) {
            self::DES        => 'des-cbc',      // key length 64bit / 8 Byte
            self::TRIPLE_DES => 'des-ede3-cbc', // 192bit / 24 Byte: Stronger, but slower
            self::AES        => 'aes-128-cfb',  // 128 Bit / 16 Byte: Standard for SNMPv3 since RFC 3826
            self::AES192, self::AES192C => 'aes-192-cfb',  // 192 Bit / 24 Byte: rarely used
            self::AES256, self::AES256C => 'aes-256-cfb',  // 256 Bit / 32 Byte: currently the most secure one
        };
    }

    public function isDES(): bool
    {
        return $this === self::DES || $this === self::TRIPLE_DES;
    }

    public function isBlumenthal(): bool
    {
        return $this === self::AES || $this === self::AES192 || $this === self::AES256;
    }
}
