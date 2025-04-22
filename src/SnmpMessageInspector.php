<?php

namespace IMEdge\Snmp;

use IMEdge\Snmp\Usm\UserBasedSecurityModel;
use IMEdge\Tests\Snmp\TestHelper;

class SnmpMessageInspector
{
    public static function dump(SnmpMessage $message): void
    {
        echo static::getDump($message);
    }

    public static function getDump(SnmpMessage $message): string
    {
        $result = sprintf("Version: %s\n", $message->getVersion());
        if ($message instanceof SnmpV1Message) {
            $result .= sprintf("Community: %s\n", $message->community);
            $result .= self::prepareVarBinds($message->getPdu()->varBinds);
        } elseif ($message instanceof SnmpV3Message) {
            if ($message->securityParameters instanceof UserBasedSecurityModel) {
                $result .= sprintf("Username: %s\n", $message->securityParameters->username ?? '-');
                $result .= sprintf("Engine ID: %s\n", TestHelper::niceHex($message->securityParameters->engineId));
                $result .= sprintf("Engine boots: %d\n", $message->securityParameters->engineBoots);
                $result .= sprintf("Engine time: %d\n", $message->securityParameters->engineTime);
                $result .= sprintf(
                    "Auth Hash: %s\n",
                    $message->securityParameters->authenticationParams
                        ? TestHelper::niceHex($message->securityParameters->authenticationParams)
                        : '-'
                );
                $result .= sprintf(
                    "Priv Salt: %s\n",
                    $message->securityParameters->privacyParams
                        ? TestHelper::niceHex($message->securityParameters->privacyParams)
                        : '-'
                );
            }
            if ($message->scopedPdu->isPlainText()) {
                $result .= self::prepareVarBinds($message->getPdu()->varBinds);
            } else {
                $result .= sprintf("Encoded PDU: %s\n", TestHelper::niceHex($message->scopedPdu->encryptedPdu));
            }
        }


        return $result;
    }

    /**
     * @param VarBind[] $varBinds
     */
    protected static function prepareVarBinds(array $varBinds): string
    {
        $result = '';
        foreach ($varBinds as $varBind) {
            $result .= sprintf(
                "%s: %s\n",
                $varBind->oid,
                $varBind->value->getReadableValue()
            );
        }

        return $result;
    }
}
