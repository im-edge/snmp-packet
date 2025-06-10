<?php

namespace IMEdge\SnmpPacket\Usm;

class AuthenticationParams
{
    protected const EMPTY_CHARACTER = "\x00";

    /**
     * @var array<string, string>
     */
    protected static array $placeHolders = [];

    public static function getPlaceHolder(SnmpAuthProtocol $authProtocol): string
    {
        return self::$placeHolders[$authProtocol->getHashAlgorithm()] ??= str_repeat(
            self::EMPTY_CHARACTER,
            $authProtocol->getTruncateOutputLength()
        );
    }
}
