<?php

namespace IMEdge\Snmp\Usm;

use IMEdge\Snmp\VarBind;

class UsmStats
{
    /**
     * usmStatsUnsupportedSecLevels
     *
     * The total number of packets received by the SNMP
     * engine which were dropped because they requested a
     * securityLevel that was unknown to the SNMP engine
     * or otherwise unavailable
     */
    public const UNSUPPORTED_SEC_LEVELS = '1.3.6.1.6.3.15.1.1.1.0';

    /**
     * usmStatsNotInTimeWindows
     *
     * The total number of packets received by the SNMP
     * engine which were dropped because they appeared
     * outside of the authoritative SNMP engine's window
     */
    public const NOT_IN_TIME_WINDOWS = '1.3.6.1.6.3.15.1.1.2.0';

    /**
     * usmStatsUnknownUserNames
     *
     * The total number of packets received by the SNMP
     * engine which were dropped because they referenced a
     * user that was not known to the SNMP engine
     */
    public const UNKNOWN_USER_NAMES = '1.3.6.1.6.3.15.1.1.3.0';

    /**
     * usmStatsUnknownEngineIDs
     *
     * The total number of packets received by the SNMP
     * engine which were dropped because they referenced an
     * snmpEngineID that was not known to the SNMP engine
     */
    public const UNKNOWN_ENGINE_IDS = '1.3.6.1.6.3.15.1.1.4.0';

    /**
     * usmStatsWrongDigests
     *
     * The total number of packets received by the SNMP
     * engine which were dropped because they didn't
     * contain the expected digest value
     */
    public const WRONG_DIGESTS = '1.3.6.1.6.3.15.1.1.5.0';

    /**
     * usmStatsDecryptionErrors
     *
     * The total number of packets received by the SNMP
     * engine which were dropped because they could not be
     * decrypted
     */
    public const DECRYPTION_ERRORS = '1.3.6.1.6.3.15.1.1.6.0';

    /**
     * @param VarBind[] $varBinds
     */
    public static function getErrorForVarBindList(array $varBinds): ?string
    {
        foreach ($varBinds as $varBind) {
            if ($error = self::getNameForOid($varBind->oid)) {
                return $error;
            }
        }

        return null;
    }

    public static function getNameForOid(string $oid): ?string
    {
        return match ($oid) {
            self::UNSUPPORTED_SEC_LEVELS => 'unsupported security level',
            self::NOT_IN_TIME_WINDOWS    => 'not in time window',
            self::UNKNOWN_USER_NAMES     => 'unknown user name',
            self::UNKNOWN_ENGINE_IDS     => 'unknown engine id',
            self::WRONG_DIGESTS          => 'wrong digest',
            self::DECRYPTION_ERRORS      => 'decryption error',
            default                      => null,
        };
    }
}
