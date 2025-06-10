<?php

namespace IMEdge\SnmpPacket\Usm;

use FreeDSx\Asn1\Encoder\BerEncoder;
use FreeDSx\Asn1\Exception\EncoderException;
use FreeDSx\Asn1\Type\IntegerType;
use FreeDSx\Asn1\Type\OctetStringType;
use FreeDSx\Asn1\Type\SequenceType;
use IMEdge\SnmpPacket\Error\SnmpParseError;
use IMEdge\SnmpPacket\Message\SnmpV3SecurityParameters;
use IMEdge\SnmpPacket\ParseHelper;

class UserBasedSecurityModel implements SnmpV3SecurityParameters
{
    protected const TIME_WINDOW_SECONDS = 150;

    protected static ?BerEncoder $encoder = null;

    public function __construct(
        public readonly string $username = '',
        public readonly string $engineId = '', // ?? -> <MISSING> -->??
        public int $engineBoots = 0,
        public int $engineTime = 0,
        public string $authenticationParams = '', // password hash
        public string $privacyParams = '' // encryption salt
    ) {
    }

    public static function create(string $username, RemoteEngine $engine, string $salt): UserBasedSecurityModel
    {
        if ($engine->hasId()) {
            return new UserBasedSecurityModel(
                $username,
                $engine->id,
                $engine->boots,
                $engine->time,
                '',
                $salt
            );
        }

        return new UserBasedSecurityModel('');
    }

    /**
     * @throws SnmpParseError
     */
    public static function fromString(string $string): UserBasedSecurityModel
    {
        self::$encoder ??= new BerEncoder();
        try {
            $sequence = ParseHelper::requireSequence(self::$encoder->decode($string), 'USM');
        } catch (EncoderException $e) {
            throw new SnmpParseError($e->getMessage(), $e->getCode(), $e);
        }

        return new UserBasedSecurityModel(
            $sequence->getChild(3)?->getValue() ?? throw new SnmpParseError('USM has no username'),
            $sequence->getChild(0)?->getValue() ?? throw new SnmpParseError('USM has no engineId'),
            $sequence->getChild(1)?->getValue() ?? throw new SnmpParseError('USM has no engineBoots'),
            $sequence->getChild(2)?->getValue() ?? throw new SnmpParseError('USM has no engineTime'),
            $sequence->getChild(4)?->getValue() ?? throw new SnmpParseError('USM has no authenticationParams'),
            $sequence->getChild(5)?->getValue() ?? throw new SnmpParseError('USM has no privacyParams'),
        );
    }

    public function __toString(): string
    {
        self::$encoder ??= new BerEncoder();
        $sequence = new SequenceType(
            new OctetStringType($this->engineId),
            new IntegerType($this->engineBoots),
            new IntegerType($this->engineTime),
            new OctetStringType($this->username), // 0..32 characters
            new OctetStringType($this->authenticationParams),
            new OctetStringType($this->privacyParams),
        );

        return self::$encoder->encode($sequence);
    }
}
