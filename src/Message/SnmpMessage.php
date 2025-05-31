<?php

namespace IMEdge\Snmp\Message;

use FreeDSx\Asn1\Encoder\BerEncoder;
use FreeDSx\Asn1\Type\SequenceType;
use IMEdge\Snmp\Pdu\Pdu;
use IMEdge\Snmp\SnmpVersion;
use InvalidArgumentException;

abstract class SnmpMessage
{
    protected static ?BerEncoder $encoder = null;

    public SnmpVersion $version;

    public static function fromAsn1(SequenceType $sequence): SnmpMessage
    {
        $version = SnmpVersion::tryFrom($sequence->getChild(0)->getValue());

        return match ($version) {
            SnmpVersion::v1  => SnmpV1Message::fromAsn1($sequence),
            SnmpVersion::v2c => SnmpV2Message::fromAsn1($sequence),
            SnmpVersion::v3  => SnmpV3Message::fromAsn1($sequence),
            null => throw new InvalidArgumentException(sprintf(
                "Unsupported message version: %s",
                $sequence->getChild(0)->getValue()
            )),
        };
    }

    abstract public function toAsn1(): SequenceType;

    abstract public function getPdu(): Pdu;

    public static function fromBinary(string $binary): SnmpMessage
    {
        self::$encoder ??= new BerEncoder();
        return static::fromAsn1(self::$encoder->decode($binary));
    }

    public function getVersion(): string
    {
        return static::VERSION->value;
    }

    public function toBinary(): string
    {
        self::$encoder ??= new BerEncoder();
        return self::$encoder->encode($this->toAsn1());
    }
}
