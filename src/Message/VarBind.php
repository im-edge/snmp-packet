<?php

namespace IMEdge\Snmp\Message;

use FreeDSx\Asn1\Type\NullType;
use FreeDSx\Asn1\Type\OidType;
use FreeDSx\Asn1\Type\SequenceType;
use IMEdge\Snmp\VarBindValue\Value;
use IMEdge\Snmp\VarBindValue\VarBindValue;
use ValueError;

class VarBind
{
    final public function __construct(
        public readonly string $oid,
        public readonly ?VarBindValue $value = null
    ) {
    }

    public function toAsn1(): SequenceType
    {
        return new SequenceType(new OidType($this->oid), $this->value?->toAsn1() ?? new NullType());
    }

    public static function fromAsn1(SequenceType $varBind): static
    {
        if ($varBind->count() !== 2) {
            throw new ValueError(sprintf(
                'Cannot construct a VarBind from a sequence with %d instead of 2 elements',
                $varBind->count()
            ));
        }
        $oid = $varBind->getChild(0);
        if (! $oid instanceof OidType) {
            throw new ValueError('VarBind required OID at pos 0');
        }
        $value = $varBind->getChild(1);
        if ($value !== null) {
            $value = Value::fromAsn1($value);
        }

        return new static($oid->getValue(), $value);
    }
}
