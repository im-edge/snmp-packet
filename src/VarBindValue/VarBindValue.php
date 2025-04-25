<?php

namespace IMEdge\Snmp\VarBindValue;

use FreeDSx\Asn1\Type\AbstractType;
use IMEdge\Json\JsonSerialization;

interface VarBindValue extends JsonSerialization
{
    public static function fromAsn1(AbstractType $type): static;
    public function toAsn1(): AbstractType;
    public function getReadableValue(): int|string;
}
