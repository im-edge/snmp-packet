<?php

namespace IMEdge\Snmp\Pdu;

use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Encoder\BerEncoder;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\IncompleteType;
use FreeDSx\Asn1\Type\IntegerType;
use FreeDSx\Asn1\Type\SequenceType;
use IMEdge\Snmp\ErrorStatus;
use IMEdge\Snmp\Message\VarBindList;
use InvalidArgumentException;
use RuntimeException;

abstract class Pdu
{
    protected static ?BerEncoder $encoder = null;
    public ErrorStatus $errorStatus = ErrorStatus::NO_ERROR;
    public int $errorIndex = 0;
    protected bool $wantsResponse = false;

    public function __construct(
        public readonly VarBindList $varBinds = new VarBindList(),
        public ?int $requestId = null
    ) {
    }

    public function wantsResponse(): bool
    {
        return $this->wantsResponse;
    }

    public function toAsn1(): AbstractType
    {
        if ($this->requestId === null) {
            throw new RuntimeException('Cannot created ASN1 type w/o requestId');
        }

        return Asn1::context(static::TAG ?? throw new RuntimeException(sprintf(
            '%s has no TAG', get_class($this)
        )), new SequenceType(
            new IntegerType($this->requestId),
            new IntegerType($this->errorStatus->value),
            new IntegerType($this->errorIndex),
            $this->varBinds->toAsn1()
        ));
    }

    public static function fromAsn1(IncompleteType $type): Pdu
    {
        self::$encoder ??= new BerEncoder();
        $sequence = self::$encoder->complete($type, AbstractType::TAG_TYPE_SEQUENCE);
        // $sequence->count() === 4;
        $varBinds = VarBindList::fromAsn1($sequence->getChild(3));
        $pdu = match ($sequence->getTagNumber()) {
            GetRequest::TAG     => new GetRequest($varBinds),
            GetNextRequest::TAG => new GetNextRequest($varBinds),
            Response::TAG       => new Response($varBinds),
            SetRequest::TAG     => new SetRequest($varBinds),
            GetBulkRequest::TAG => new GetBulkRequest($varBinds),
            InformRequest::TAG  => new InformRequest($varBinds),
            TrapV2::TAG         => new TrapV2($varBinds),
            Report::TAG         => new Report($varBinds),
            default             => throw new InvalidArgumentException(sprintf(
                'Invalid PDU tag %s',
                $sequence->getTagNumber()
            )),
        };

        $pdu->requestId = $sequence->getChild(0)->getValue();
        $pdu->errorStatus = ErrorStatus::from($sequence->getChild(1)->getValue());
        $pdu->errorIndex = $sequence->getChild(2)->getValue();

        return $pdu;
    }
}
