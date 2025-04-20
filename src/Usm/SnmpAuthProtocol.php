<?php

namespace IMEdge\Snmp\Usm;

/**
 * Related references:
 *
 * usmHMAC128SHA224AuthProtocol: M=28, N=16, H=SHA-224;
 * usmHMAC192SHA256AuthProtocol: M=32, N=24, H=SHA-256;
 * usmHMAC256SHA384AuthProtocol: M=48, N=32, H=SHA-384;
 * usmHMAC384SHA512AuthProtocol: M=64, N=48, H=SHA-512.
 */
enum SnmpAuthProtocol: string
{
    // RFC 3414, required:
    case MD5  = 'md5';
    // RFC 3414, optional:
    case SHA1 = 'sha1';
    // RFC 7860:
    case SHA224 = 'sha224';
    case SHA256 = 'sha256';
    case SHA384 = 'sha384';
    case SHA512 = 'sha512';

    public function getHashAlgorithm(): string
    {
        return $this->value;
    }

    public function getTruncateOutputLength(): int
    {
        /**
         * RFC 7860, Section 4.1 / 4.2
         */
        return match ($this) {
            self::MD5, self::SHA1 => 12,
            self::SHA224 => 16,
            self::SHA256 => 24,
            self::SHA384 => 32,
            self::SHA512 => 48,
        };
    }
    public function getTruncateSecretKeyLength(): int
    {
        /**
         * RFC 7860, Section 4.1 / 4.2
         */
        return match ($this) {
            self::MD5 => 16,
            self::SHA1 => 20,
            self::SHA224 => 28,
            self::SHA256 => 32,
            self::SHA384 => 48,
            self::SHA512 => 64,
        };
    }
}
