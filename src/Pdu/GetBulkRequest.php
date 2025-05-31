<?php

namespace IMEdge\Snmp\Pdu;

use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\IntegerType;
use FreeDSx\Asn1\Type\SequenceType;
use IMEdge\Snmp\Message\VarBindList;
use RuntimeException;

/**
 * GetBulkRequest
 *
 * Has been added in SNMPv2
 */
class GetBulkRequest extends Pdu
{
    public const TAG = 5;

    protected bool $wantsResponse = true;

    public function __construct(
        VarBindList $varBinds,
        ?int $requestId = null,
        public readonly int $maxRepetitions = 10,
        public readonly int $nonRepeaters = 0
    ) {
        parent::__construct($varBinds, $requestId);
    }

    public function toAsn1(): AbstractType
    {
        if ($this->requestId === null) {
            throw new RuntimeException('Cannot created ASN1 type w/o requestId');
        }
        return Asn1::context(static::TAG, new SequenceType(
            new IntegerType($this->requestId),
            new IntegerType($this->nonRepeaters),
            new IntegerType($this->maxRepetitions),
            $this->varBinds->toAsn1()
        ));
    }

    // TODO: fromASN1
}
