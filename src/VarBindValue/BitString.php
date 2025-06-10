<?php

namespace IMEdge\SnmpPacket\VarBindValue;

use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\BitStringType;
use IMEdge\Json\JsonString;
use stdClass;
use ValueError;

use function is_object;
use function is_string;

/**
 * The BITS construct (RFC 2578 7.1.4)
 * -----------------------------------
 *
 * The BITS construct represents an enumeration of named bits.  This
 * collection is assigned non-negative, contiguous (but see below)
 * values, starting at zero.  Only those named-bits so enumerated may be
 * present in a value.  (Thus, enumerations must be assigned to
 * consecutive bits; however, see Section 9 for refinements of an object
 * with this syntax.)
 *
 * As part of updating an information module, for an object defined
 * using the BITS construct, new enumerations can be added or existing
 * enumerations can have new labels assigned to them.  After an
 * enumeration is added, it might not be possible to distinguish between
 * an implementation of the updated object for which the new enumeration
 * is not asserted, and an implementation of the object prior to the
 * addition.  Depending on the circumstances, such an ambiguity could
 * either be desirable or could be undesirable.  The means to avoid such
 * an ambiguity is dependent on the encoding of values on the wire;
 * however, one possibility is to define new enumerations starting at
 * the next multiple of eight bits.  (Of course, this can also result in
 * the enumerations no longer being contiguous.)
 *
 * Although there is no SMI-specified limitation on the number of
 * enumerations (and therefore on the length of a value), except as may
 * be imposed by the limit on the length of an OCTET STRING, MIB
 * designers should realize that there may be implementation and
 * interoperability limitations for sizes in excess of 128 bits.
 *
 * Finally, a label for a named-number enumeration must consist of one
 * or more letters or digits, up to a maximum of 64 characters, and the
 * initial character must be a lower-case letter.  (However, labels
 * longer than 32 characters are not recommended.)  Note that hyphens
 * are not allowed by this specification.
 */
class BitString implements VarBindValue
{
    public const NAME = 'bit_string';

    final public function __construct(
        public readonly string $value
    ) {
    }

    public static function fromAsn1(AbstractType $type): static
    {
        if (! $type instanceof BitStringType) {
            throw new ValueError('BitString requires a BitStringType');
        }

        return new static($type->getValue());
    }

    public function toAsn1(): AbstractType
    {
        return new BitStringType($this->value);
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
