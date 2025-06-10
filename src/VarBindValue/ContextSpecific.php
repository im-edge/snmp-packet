<?php

namespace IMEdge\SnmpPacket\VarBindValue;

use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Encoder\BerEncoder;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\IncompleteType;
use FreeDSx\Asn1\Type\IntegerType;
use FreeDSx\Asn1\Type\NullType;
use stdClass;
use ValueError;

/**
 *
 * From RFC2089:
 *
 * - For SNMP GET requests we can get back noSuchObject and noSuchInstance
 * - For SNMP GETNEXT requests we can get back endOfMibView
 * - For SNMP SET requests we cannot get back any exceptions
 *
 * - For SNMP GETBULK requests we can get back endOfMibView, but such a request should only come in as an SNMPv2
 *   request, so we do not have to worry about any mapping onto SNMPv1. If a GETBULK comes in as an SNMPv1 request, it
 *   is treated as an error and the packet is dropped
 */
class ContextSpecific implements VarBindValue
{
    protected static BerEncoder $encoder;

    final protected function __construct(
        public readonly ContextSpecificError $value
    ) {
    }

    public static function fromAsn1(AbstractType $type): static
    {
        self::$encoder ??= new BerEncoder();
        if ($type instanceof IncompleteType) {
            $type = self::$encoder->complete($type, AbstractType::TAG_TYPE_NULL);
        }
        assert(is_int($type->getTagNumber()));

        return new static(ContextSpecificError::from($type->getTagNumber()));
    }

    public function toAsn1(): AbstractType
    {
        return Asn1::context($this->value->value, new NullType());
    }

    public function getReadableValue(): int
    {
        return $this->value->value;
    }

    public function jsonSerialize(): stdClass
    {
        return (object) [
            'type'  => 'context_specific',
            'value' => $this->getReadableValue(),
        ];
    }

    public static function fromSerialization($any): ContextSpecific
    {
        if (! is_object($any) || !isset($any->value)) {
            throw new ValueError('Cannot initialize from ' . json_encode($any));
        }

        return new static(ContextSpecificError::from($any->value));
    }
}
