<?php

namespace IMEdge\Snmp\Usm;

use IMEdge\Snmp\Snmpv3SecurityParameters;
use Sop\ASN1\Type\Primitive\OctetString;

/**
 * Unused
 */
class NoSecurityModel implements Snmpv3SecurityParameters
{
    protected static ?string $rawString = null;

    protected static function generateRawString(): string
    {
        return (new OctetString(''))->toDER();
    }

    public function __toString(): string
    {
        return self::$rawString ??= self::generateRawString();
    }
}
