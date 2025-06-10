<?php

namespace IMEdge\SnmpPacket\VarBindValue;

use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\OctetStringType;
use ValueError;

/**
 * Opaque (RFC 2578 7.1.9)
 * -----------------------
 *
 * The Opaque type is provided solely for backward-compatibility, and
 * shall not be used for newly-defined object types.
 *
 * The Opaque type supports the capability to pass arbitrary ASN.1
 * syntax.  A value is encoded using the ASN.1 Basic Encoding Rules [4]
 * into a string of octets.  This, in turn, is encoded as an OCTET
 * STRING, in effect "double-wrapping" the original ASN.1 value.
 *
 * Note that a conforming implementation need only be able to accept and
 * recognize opaquely-encoded data.  It need not be able to unwrap the
 * data and then interpret its contents.
 *
 * A requirement on "standard" MIB modules is that no object may have a
 * SYNTAX clause value of Opaque.
 */
class Opaque extends ApplicationValue
{
    public const TAG = 4;
    public const NAME = 'opaque';

    final protected function __construct(
        protected readonly string $value
    ) {
    }

    public function getReadableValue(): string
    {
        return JsonHelper::stringForJson($this->value);
    }

    public static function fromAsn1(AbstractType $type): static
    {
        if (! $type instanceof OctetStringType) {
            throw new ValueError('Opaque requires an OctetString');
        }

        return new static($type->getValue());
    }

    public function toAsn1(): AbstractType
    {
        return Asn1::application(self::TAG, Asn1::octetString($this->value));
    }

    public static function fromReadableValue(int|string $value): static
    {
        if (is_int($value)) {
            throw new ValueError('Opaque needs a string');
        }

        return new static(JsonHelper::stringFromJson($value));
    }
}
