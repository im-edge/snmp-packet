<?php

namespace IMEdge\Snmp\Usm;

use function floor;
use function hash;
use function str_repeat;
use function strlen;
use function substr;

class AuthKey
{
    protected const MEGA_BYTE = 1048576;

    public static function generate(SnmpAuthProtocol $authProtocol, string $pass, string $engineId): string
    {
        $intermediate = self::intermediate($authProtocol, $pass);
        return hash($authProtocol->getHashAlgorithm(), $intermediate . $engineId . $intermediate, true);
    }

    public static function fillOneMegaByte(string $string): string
    {
        return str_repeat($string, (int) floor(self::MEGA_BYTE / strlen($string)))
            . substr($string, 0, self::MEGA_BYTE % strlen($string));
    }

    public static function intermediate(SnmpAuthProtocol $authProtocol, string $pass): string
    {
        return hash($authProtocol->getHashAlgorithm(), self::fillOneMegaByte($pass), true);
    }
}
