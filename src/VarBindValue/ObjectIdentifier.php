<?php

namespace IMEdge\SnmpPacket\VarBindValue;

use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\OctetStringType;
use FreeDSx\Asn1\Type\OidType;
use IMEdge\Json\JsonString;
use stdClass;
use ValueError;

use function is_object;
use function is_string;

/**
 * OBJECT IDENTIFIER (RFC 2578 7.1.3)
 * ----------------------------------
 *
 * The OBJECT IDENTIFIER type represents administratively assigned
 * names.  Any instance of this type may have at most 128 sub-
 * identifiers.  Further, each sub-identifier must not exceed the value
 * 2^32-1 (4294967295 decimal).
 */
class ObjectIdentifier implements VarBindValue
{
    public const NAME = 'oid';

    final public function __construct(
        public readonly string $value
    ) {
    }

    public static function fromAsn1(AbstractType $type): static
    {
        if (! $type instanceof OidType) {
            throw new ValueError('ObjectIdentifier requires an OidType');
        }

        return new static($type->getValue());
    }

    public function toAsn1(): AbstractType
    {
        return new OidType($this->value);
    }

    public function jsonSerialize(): stdClass
    {
        return (object) [
            'type'  => static::NAME,
            'value' => $this->getReadableValue(),
        ];
    }

    public function getReadableValue(): string
    {
        return $this->value;
    }

    public static function fromSerialization($any): static
    {
        if ((! is_object($any)) || ($any->type ?? null !== static::NAME) || ! is_string($any->value ?? null)) {
            throw new ValueError('Cannot initialize from ' . JsonString::encode($any));
        }

        return new static($any->value);
    }
}
