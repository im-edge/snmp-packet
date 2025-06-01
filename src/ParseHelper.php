<?php

namespace IMEdge\Snmp;

use FreeDSx\Asn1\Type\IncompleteType;
use FreeDSx\Asn1\Type\SequenceType;
use IMEdge\Snmp\Error\SnmpParseError;

class ParseHelper
{
    /**
     * @throws SnmpParseError
     */
    public static function requireSequence(mixed $type, string $label): SequenceType
    {
        if ($type instanceof SequenceType) {
            return $type;
        }

        throw new SnmpParseError(sprintf('Sequence expected for %s, got %s', $label, get_debug_type($type)));
    }

    /**
     * @throws SnmpParseError
     */
    public static function requireIncomplete(mixed $type, string $label): IncompleteType
    {
        if ($type instanceof IncompleteType) {
            return $type;
        }

        throw new SnmpParseError(sprintf('IncompleteType expected for %s, got %s', $label, get_debug_type($type)));
    }
}
