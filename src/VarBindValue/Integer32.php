<?php

namespace IMEdge\SnmpPacket\VarBindValue;

use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\IntegerType;
use stdClass;
use ValueError;

/**
 * Integer32 and INTEGER (RFC 2578 7.1.1)
 * --------------------------------------
 *
 * The Integer32 type represents integer-valued information between
 * -2^31 and 2^31-1 inclusive (-2147483648 to 2147483647 decimal).  This
 * type is indistinguishable from the INTEGER type.  Both the INTEGER
 * and Integer32 types may be sub-typed to be more constrained than the
 * Integer32 type.
 *
 * The INTEGER type (but not the Integer32 type) may also be used to
 * represent integer-valued information as named-number enumerations.
 * In this case, only those named-numbers so enumerated may be present
 * as a value.  Note that although it is recommended that enumerated
 * values start at 1 and be numbered contiguously, any valid value for
 * Integer32 is allowed for an enumerated value and, further, enumerated
 * values needn't be contiguously assigned.
 *
 * Finally, a label for a named-number enumeration must consist of one
 * or more letters or digits, up to a maximum of 64 characters, and the
 * initial character must be a lower-case letter.  (However, labels
 * longer than 32 characters are not recommended.)  Note that hyphens
 * are not allowed by this specification (except for use by information
 * modules converted from SMIv1 which did allow hyphens).
 */
class Integer32 implements VarBindValue
{
    public const NAME = 'integer32';

    final public function __construct(
        protected int $value
    ) {
        if ($value < -2147483648 || $value > 2147483647) {
            throw new ValueError(sprintf(
                '%s is not a valid Integer32/INTEGER value',
                $value
            ));
        }
    }

    public static function fromAsn1(AbstractType $type): static
    {
        if (! $type instanceof IntegerType) {
            throw new ValueError('Integer32 requires an IntegerType');
        }

        return new static($type->getValue());
    }

    public function toAsn1(): AbstractType
    {
        return new IntegerType($this->value);
    }

    public function jsonSerialize(): stdClass
    {
        return (object) [
            'type'  => static::NAME,
            'value' => $this->getReadableValue(),
        ];
    }

    public function getReadableValue(): int
    {
        return $this->value;
    }

    public static function fromSerialization($any): static
    {
        if ((! is_object($any)) || ($any->type ?? null !== static::NAME) || ! is_int($any->value ?? null)) {
            throw new ValueError('Cannot initialize from ' . json_encode($any));
        }

        return new static($any->value);
    }
}
