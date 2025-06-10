<?php

namespace IMEdge\SnmpPacket\Message;

use FreeDSx\Asn1\Type\OctetStringType;
use FreeDSx\Asn1\Type\SequenceType;
use IMEdge\SnmpPacket\Error\SnmpEncodingError;
use IMEdge\SnmpPacket\Error\SnmpParseError;
use IMEdge\SnmpPacket\ParseHelper;
use IMEdge\SnmpPacket\Pdu\Pdu;

class Snmpv3ScopedPdu
{
    public ?Pdu $pdu = null;
    public ?string $contextEngineId = null;
    public ?string $contextName = null;
    public ?string $encryptedPdu = null;

    final protected function __construct()
    {
    }

    public static function forPdu(Pdu $pdu, string $contextEngineId = '', string $contextName = ''): Snmpv3ScopedPdu
    {
        $self = new Snmpv3ScopedPdu();
        $self->pdu = $pdu;
        $self->contextEngineId = $contextEngineId;
        $self->contextName = $contextName;

        return $self;
    }

    public static function encrypted(string $encryptedPdu, ?Pdu $pdu = null): Snmpv3ScopedPdu
    {
        $self = new Snmpv3ScopedPdu();
        $self->pdu = $pdu;
        $self->encryptedPdu = $encryptedPdu;

        return $self;
    }

    public function isPlainText(): bool
    {
        return $this->encryptedPdu === null;
    }

    /**
     * @throws SnmpEncodingError
     */
    public function toAsn1(): SequenceType|OctetStringType
    {
        if ($this->encryptedPdu === null) {
            if ($this->pdu === null) {
                throw new SnmpEncodingError('Cannot encode empty scoped PDU');
            }
            return new SequenceType(
                new OctetStringType($this->contextEngineId ?? ''),
                new OctetStringType($this->contextName ?? ''),
                $this->pdu->toAsn1(),
            );
        }

        return new OctetStringType($this->encryptedPdu);
    }

    /**
     * @throws SnmpParseError
     */
    public static function fromAsn1(SequenceType|OctetStringType $encoded): Snmpv3ScopedPdu
    {
        $self = new Snmpv3ScopedPdu();
        if ($encoded instanceof SequenceType) {
            $self->pdu = Pdu::fromAsn1(ParseHelper::requireIncomplete($encoded->getChild(2), 'PDU'));
            $self->contextEngineId = $encoded->getChild(0)?->getValue();
            $self->contextName = $encoded->getChild(1)?->getValue();
        } else {
            $self->encryptedPdu = $encoded->getValue();
        }
        // ScopedPDU ::= SEQUENCE {
        //   contextEngineID  OCTET STRING,
        //   contextName      OCTET STRING,
        //   data             ANY -- e.g., PDUs as defined in [RFC3416]
        // }

        return $self;
    }
}
