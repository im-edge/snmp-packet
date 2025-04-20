<?php

namespace IMEdge\Snmp;

use Sop\ASN1\Type\Constructed\Sequence;
use Sop\ASN1\Type\Primitive\OctetString;

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

    public function toASN1(): Sequence|OctetString
    {
        if ($this->encryptedPdu === null) {
            if ($this->pdu === null) {
                throw new \RuntimeException('Cannot encode empty scoped PDU');
            }
            return new Sequence(
                new OctetString($this->contextEngineId ?? ''),
                new OctetString($this->contextName ?? ''),
                $this->pdu->toASN1(),
            );
        }

        return new OctetString($this->encryptedPdu);
    }

    public static function fromAsn1(Sequence|OctetString $encoded): Snmpv3ScopedPdu
    {
        $self = new Snmpv3ScopedPdu();
        if ($encoded instanceof Sequence) {
            $self->pdu = Pdu::fromASN1($encoded->at(2)->asTagged());
            $self->contextEngineId = $encoded->at(0)->asOctetString();
            $self->contextName = $encoded->at(1)->asOctetString();
        } else {
            $self->encryptedPdu = $encoded->string();
        }
        // ScopedPDU ::= SEQUENCE {
        //   contextEngineID  OCTET STRING,
        //   contextName      OCTET STRING,
        //   data             ANY -- e.g., PDUs as defined in [RFC3416]
        // }

        return $self;
    }
}
