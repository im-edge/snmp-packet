<?php

namespace IMEdge\Snmp\Usm;

use IMEdge\Snmp\Message\Snmpv3SecurityParameters;

/**
 * Unused
 */
class NoSecurityModel implements Snmpv3SecurityParameters
{
    protected static ?string $rawString = null;

    public function __toString(): string
    {
        return self::$rawString ??= '';
    }
}
