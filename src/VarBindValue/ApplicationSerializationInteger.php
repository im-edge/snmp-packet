<?php

namespace IMEdge\Snmp\VarBindValue;

use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\IntegerType;
use ValueError;

use function ucfirst;

trait ApplicationSerializationInteger
{
    public static function fromAsn1(AbstractType $type): static
    {
        assert(is_int($type->getTagNumber()));
        if (! $type instanceof IntegerType) {
            throw new ValueError(sprintf(
                '%s requires an IntegerType, got %s',
                ucfirst(static::NAME),
                get_class($type)
            ));
        }

        return new static($type->getValue());
    }

    public function toAsn1(): AbstractType
    {
        return IntegerType::withTag(static::TAG, AbstractType::TAG_CLASS_APPLICATION, $this->value);
    }

    public static function fromReadableValue(int|string $value): static
    {
        return new static($value);
    }

    public function getReadableValue(): int|string
    {
        return $this->value;
    }
}
