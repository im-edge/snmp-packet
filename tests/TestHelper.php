<?php

namespace IMEdge\Tests\Snmp;

use InvalidArgumentException;

class TestHelper
{
    public static function unHex(string $string): string
    {
        $bin = hex2bin(str_replace([' ', "\n"], '', $string));
        if ($bin === false) {
            throw new InvalidArgumentException("Unable to unHex: $string");
        }

        return $bin;
    }

    public static function niceHex(mixed $string): string
    {
        if ($string === null) {
            return '(null)';
        }
        if (! is_string($string)) {
            return get_debug_type($string);
        }

        return wordwrap(implode(' ', array_map(bin2hex(...), str_split($string))), 120);
    }
}
