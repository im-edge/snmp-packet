<?php

namespace IMEdge\Tests\Snmp;

class TestHelper
{
    public static function unHex(string $string): string
    {
        return hex2bin(str_replace(' ', '', $string));
    }

    public static function niceHex(mixed $string): string
    {
        if ($string === null) {
            return '(null)';
        }
        if (! is_string($string)) {
            return get_debug_type($string);
        }

        return implode(' ', array_map(bin2hex(...), str_split($string)));
    }
}
