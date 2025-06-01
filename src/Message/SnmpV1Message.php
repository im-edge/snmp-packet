<?php

namespace IMEdge\Snmp\Message;

use FreeDSx\Asn1\Type\IntegerType;
use FreeDSx\Asn1\Type\OctetStringType;
use FreeDSx\Asn1\Type\SequenceType;
use IMEdge\Snmp\Error\SnmpParseError;
use IMEdge\Snmp\ParseHelper;
use IMEdge\Snmp\Pdu\Pdu;
use IMEdge\Snmp\SnmpVersion;

class SnmpV1Message extends SnmpMessage
{
    public const VERSION = SnmpVersion::v1;

    final public function __construct(
        #[\SensitiveParameter]
        public readonly string $community,
        public Pdu $pdu
    ) {
    }

    public function toAsn1(): SequenceType
    {
        return new SequenceType(
            new IntegerType(self::VERSION->value),
            new OctetStringType($this->community),
            $this->pdu->toAsn1()
        );
    }

    public function getPdu(): Pdu
    {
        return $this->pdu;
    }

    /**
     * @throws SnmpParseError
     */
    public static function fromAsn1(SequenceType $sequence): static
    {
        return new static(
            $sequence->getChild(1)?->getValue() ?? throw new SnmpParseError('Got no Community'),
            Pdu::fromAsn1(ParseHelper::requireIncomplete($sequence->getChild(2), 'PDU'))
        );
    }
}
