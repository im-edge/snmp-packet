<?php

namespace IMEdge\SnmpPacket\VarBindValue;

use FreeDSx\Asn1\Encoder\BerEncoder;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\IncompleteType;
use stdClass;
use ValueError;

abstract class ApplicationValue implements VarBindValue
{
    public const NAME = 'unspecified-by-child-class';

    public static function fromSerialization($any): static
    {
        if (! is_object($any)) {
            throw new ValueError('Cannot initialize from ' . json_encode($any));
        }

        $self = match ($any->type ?? 'unspecified') {
            IpAddress::NAME   => IpAddress::fromReadableValue($any->value ?? ''),
            Counter32::NAME   => Counter32::fromReadableValue($any->value ?? ''),
            Gauge32::NAME     => Gauge32::fromReadableValue($any->value ?? ''),
            TimeTicks::NAME   => TimeTicks::fromReadableValue($any->value ?? ''),
            Opaque::NAME      => Opaque::fromReadableValue($any->value ?? ''),
            NsapAddress::NAME => NsapAddress::fromReadableValue($any->value ?? ''),
            Counter64::NAME   => Counter64::fromReadableValue($any->value ?? ''),
            Unsigned32::NAME  => Unsigned32::fromReadableValue($any->value ?? ''),
            default => throw new ValueError('Serialized ApplicationValue expected, got ' . json_encode($any))
        };
        assert($self instanceof static);

        return $self;
    }

    /**
     * @return stdClass{type: string, value: string}
     */
    public function jsonSerialize(): stdClass
    {
        return (object) [
            'type'  => static::NAME,
            'value' => $this->getReadableValue(),
        ];
    }

    abstract public static function fromReadableValue(int|string $value): static;
    abstract public function getReadableValue(): int|string;
}
