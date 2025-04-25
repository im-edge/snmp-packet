<?php

namespace IMEdge\Snmp\VarBindValue;

use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\OctetStringType;
use IMEdge\Json\JsonString;
use stdClass;
use ValueError;

use function is_object;
use function is_string;

/**
 * OCTET STRING (RFC 2578 7.1.2)
 * -----------------------------
 *
 * The OCTET STRING type represents arbitrary binary or textual data.
 * Although the SMI-specified size limitation for this type is 65535
 * octets, MIB designers should realize that there may be implementation
 * and interoperability limitations for sizes in excess of 255 octets.
 */
class OctetString implements VarBindValue
{
    public const NAME = 'octet_string';

    final public function __construct(
        public readonly string $value
    ) {
    }

    public static function fromAsn1(AbstractType $type): static
    {
        if (! $type instanceof OctetStringType) {
            throw new ValueError('OctetString requires an OctetStringType');
        }

        return new static($type->getValue());
    }

    public function toAsn1(): AbstractType
    {
        return new OctetStringType($this->value);
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
        return JsonHelper::stringForJson($this->value);
    }

    public static function fromSerialization($any): static
    {
        if ((! is_object($any)) || ($any->type ?? null !== static::NAME) || ! is_string($any->value ?? null)) {
            throw new ValueError('Cannot initialize from ' . JsonString::encode($any));
        }

        return new static(JsonHelper::stringFromJson($any->value));
    }
}
