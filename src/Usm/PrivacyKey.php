<?php

namespace IMEdge\Snmp\Usm;

use function hash;

class PrivacyKey
{
    public static function generate(
        SnmpAuthProtocol $authProtocol,
        SnmpPrivProtocol $privacyProtocol,
        #[\SensitiveParameter]
        string $password,
        string $engineId
    ): string {
        $digest = self::digest($authProtocol, $password);
        $key = hash($authProtocol->getHashAlgorithm(), $digest . $engineId . $digest, true);
        if ($privacyProtocol->isBlumenthal()) {
            // https://tools.ietf.org/html/draft-blumenthal-aes-usm-04
            while (strlen($key) < $privacyProtocol->getRequiredKeyLength()) {
                $key .= hash($authProtocol->getHashAlgorithm(), $key, true);
            }
        } else {
            // https://tools.ietf.org/html/draft-reeder-snmpv3-usm-3desede-00
            while (strlen($key) < $privacyProtocol->getRequiredKeyLength()) {
                $digest = self::digest($authProtocol, $key);
                $key .= hash($authProtocol->getHashAlgorithm(), $digest . $engineId . $digest, true);
            }
        }

        return substr($key, 0, $privacyProtocol->getRequiredKeyLength());
    }

    /**
     * This is digest1 in RFC 3414, A.1 2)
     */
    public static function digest(
        SnmpAuthProtocol $authProtocol,
        #[\SensitiveParameter]
        string $password
    ): string {
        $repeatedPwd = AuthKey::fillOneMegaByte($password);
        return hash($authProtocol->getHashAlgorithm(), $repeatedPwd, true);
    }
}
