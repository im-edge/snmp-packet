<?php

namespace IMEdge\SnmpPacket\VarBindValue;

use ValueError;

class JsonHelper
{
    public static function stringForJson(string $string): string
    {
        if (!str_starts_with($string, '0x') && self::isUtf8Safe($string)) {
            return $string;
        }

        return '0x' . bin2hex($string);
    }

    public static function stringFromJson(string $string): string
    {
        if (str_starts_with($string, '0x')) {
            $binary = @hex2bin(substr($string, 2));
            if ($binary === false) {
                throw new ValueError("Cannot decode '$string'");
            }

            return $binary;
        }

        return $string;
    }

    protected static function isUtf8Safe(string $string): bool
    {
        return preg_match('//u', $string) !== false;
    }
}
