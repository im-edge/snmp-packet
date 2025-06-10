<?php

namespace IMEdge\SnmpPacket\Usm;

use IMEdge\SnmpPacket\Message\SnmpV3SecurityParameters;

/**
 * Unused
 */
class NoSecurityModel implements SnmpV3SecurityParameters
{
    protected static ?string $rawString = null;

    public function __toString(): string
    {
        return self::$rawString ??= '';
    }
}
