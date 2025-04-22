<?php

namespace IMEdge\Snmp\Usm;

use RuntimeException;

use function openssl_decrypt;
use function openssl_encrypt;
use function openssl_error_string;
use function substr;

class PrivacyModule
{
    public readonly string $privacyKey;
    public readonly string $shortenedPrivacyKey;
    public readonly ?string $preIv;

    public function __construct(
        #[\SensitiveParameter]
        protected readonly string $password,
        protected readonly RemoteEngine $engine,
        SnmpAuthProtocol $authProtocol,
        public readonly SnmpPrivProtocol $privacyProtocol,
    ) {
        $this->privacyKey = PrivacyKey::generate($authProtocol, $privacyProtocol, $this->password, $this->engine->id);

        $keyLength = $privacyProtocol->getKeyLength();
        $this->shortenedPrivacyKey = substr($this->privacyKey, 0, $keyLength);
        if ($privacyProtocol->isDES()) {
            $this->preIv = substr($this->privacyKey, $keyLength, 8);
        } else {
            $this->preIv = null;
        }
    }

    public function encrypt(string $plain, string $salt): string
    {
        // Not needed, OpenSSL adds padding for CBC (used by DES) w/o OPENSSL_*_PADDING
        // // Padding like in PKCS#7, block size 8 bytes for DES
        // if ($this->privacyProtocol->needsPadding()) {
        //     $blockSize = 8;
        //     $padLength = $blockSize - (\strlen($plain) % $blockSize);
        //     $plain .= \str_repeat(\chr($padLength), $padLength);
        // }

        $encrypted = openssl_encrypt(
            $plain,
            $this->privacyProtocol->getOpenSslCipherAlgo(),
            $this->shortenedPrivacyKey,
            OPENSSL_RAW_DATA,
            $this->createIvFromSalt($salt)
        );

        if ($encrypted === false) {
            throw new RuntimeException('OpenSSL encryption failed: ' . openssl_error_string());
        }

        return $encrypted;
    }

    public function decrypt(string $encrypted, string $salt): string
    {
        $plain = openssl_decrypt(
            $encrypted,
            $this->privacyProtocol->getOpenSslCipherAlgo(),
            $this->shortenedPrivacyKey,
            OPENSSL_RAW_DATA,
            $this->createIvFromSalt($salt)
        );

        if ($plain === false) {
            throw new RuntimeException('OpenSSL decryption failed: ' . openssl_error_string());
        }

        // OpenSSL strips the padding w/o OPENSSL_*_PADDING, so this is not needed:
        // if ($this->privacyProtocol->needsPadding()) {
        //     // Remove PKCS#7 padding
        //     $padLength = ord($plain[-1]); // Last byte: padding length
        //     if ($padLength > 8) {
        //         throw new RuntimeException("Invalid padding");
        //     }
        //
        //     return substr($plain, 0, -$padLength);
        // }

        return $plain;
    }

    protected function createIvFromSalt(string $salt): string
    {
        if ($this->preIv === null) {
            return pack('NN', $this->engine->boots, $this->engine->time) . $salt;
        } else {
            return $salt ^ $this->preIv;
        }
    }
}
