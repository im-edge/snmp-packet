<?php

namespace IMEdge\SnmpPacket\Message;

use FreeDSx\Asn1\Type\IntegerType;
use FreeDSx\Asn1\Type\OctetStringType;
use FreeDSx\Asn1\Type\SequenceType;
use IMEdge\SnmpPacket\Error\SnmpParseError;
use IMEdge\SnmpPacket\ParseHelper;
use IMEdge\SnmpPacket\Pdu\Pdu;
use IMEdge\SnmpPacket\SecurityModel;
use IMEdge\SnmpPacket\SnmpVersion;
use IMEdge\SnmpPacket\Usm\UserBasedSecurityModel;
use RuntimeException;

class SnmpV3Message extends SnmpMessage
{
    public const VERSION = SnmpVersion::v3;

    // TODO: Should we really require a full header, or just some params?
    final public function __construct(
        public readonly SnmpV3Header $header,
        public readonly SnmpV3SecurityParameters $securityParameters, // defined by security model
        public readonly SnmpV3ScopedPdu $scopedPdu, // Or encrypted PDU -> OctetString
    ) {
    }

    public function getPdu(): Pdu
    {
        if ($this->scopedPdu->pdu === null) {
            if ($this->scopedPdu->encryptedPdu !== null) {
                throw new RuntimeException('Cannot access plain PDU, but there is an encrypted one');
            }

            throw new RuntimeException('ScopedPdu is empty');
        }
        return $this->scopedPdu->pdu;
    }

    public function toAsn1(): SequenceType
    {
        return new SequenceType(
            new IntegerType(self::VERSION->value),
            $this->header->toAsn1(),
            new OctetStringType((string) $this->securityParameters),
            $this->scopedPdu->toAsn1()
        );
    }

    /**
     * @throws SnmpParseError
     */
    public static function fromAsn1(SequenceType $sequence): static
    {
        if ($sequence->count() !== 4) {
            throw new \InvalidArgumentException('An SNMPv3 message must consist of 4 elements');
        }
        // TODO: should we protect this method, allowing us to disable this (redundant) check?
        // if ($sequence->at(0)->asInteger()->intNumber() !== self::SNMP_V3) {
        //    throw new \InvalidArgumentException('This is not an SNMPv3 packet')
        // }

        // RFC 3412, page 18
        // SNMPv3Message ::= SEQUENCE {
        // -- identify the layout of the SNMPv3Message
        // -- this element is in same position as in SNMPv1
        // -- and SNMPv2c, allowing recognition
        // -- the value 3 is used for snmpv3
        // msgVersion INTEGER ( 0 .. 2147483647 ),
        // HeaderData administrative parameters:
        // security model-specific parameters
        // format defined by Security Model:
        // ScopedPduData:
        $header = SnmpV3Header::fromAsn1(
            ParseHelper::requireSequence($sequence->getChild(1), 'header')
        );
        if ($header->securityModel === SecurityModel::USM) {
            $securityModel = UserBasedSecurityModel::fromString(
                $sequence->getChild(2)?->getValue() ?? throw new SnmpParseError('USM is missing')
            );
        } else {
            throw new SnmpParseError('Unsupported security model: ' . $header->securityModel->name);
        }

        return new static($header, $securityModel, SnmpV3ScopedPdu::fromAsn1(
            ParseHelper::requireOctetStringOrSequence($sequence->getChild(3), 'scoped PDU')
        ));
    }
}
