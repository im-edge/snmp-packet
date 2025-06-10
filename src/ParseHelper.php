<?php

namespace IMEdge\SnmpPacket;

use FreeDSx\Asn1\Type\IncompleteType;
use FreeDSx\Asn1\Type\OctetStringType;
use FreeDSx\Asn1\Type\SequenceType;
use IMEdge\SnmpPacket\Error\SnmpParseError;

class ParseHelper
{
    /**
     * @throws SnmpParseError
     */
    public static function requireOctetString(mixed $type, string $label): OctetStringType
    {
        if ($type instanceof OctetStringType) {
            return $type;
        }

        throw new SnmpParseError(sprintf('OctetString expected for %s, got %s', $label, get_debug_type($type)));
    }

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
    public static function requireOctetStringOrSequence(mixed $type, string $label): OctetStringType|SequenceType
    {
        if ($type instanceof OctetStringType || $type instanceof SequenceType) {
            return $type;
        }

        throw new SnmpParseError(sprintf(
            'OctetString or Sequence expected for %s, got %s',
            $label,
            get_debug_type($type)
        ));
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
