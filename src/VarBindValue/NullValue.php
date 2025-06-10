<?php

namespace IMEdge\SnmpPacket\VarBindValue;

use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\NullType;
use stdClass;

class NullValue implements VarBindValue
{
    final public function __construct()
    {
    }

    public static function fromSerialization($any): static
    {
        return new static();
    }

    public static function fromAsn1(AbstractType $type): static
    {
        return new static();
    }

    public function toAsn1(): AbstractType
    {
        return new NullType();
    }

    public function getReadableValue(): int|string
    {
        return '(null)';
    }

    public function jsonSerialize(): stdClass
    {
        return (object) [
            'type'  => 'null',
            'value' => null,
        ];
    }
}
