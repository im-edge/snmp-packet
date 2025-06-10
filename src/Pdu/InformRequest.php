<?php

namespace IMEdge\SnmpPacket\Pdu;

/**
 * InformRequest
 *
 * Has been added in SNMPv2
 */
class InformRequest extends Pdu
{
    public const TAG = 6;
    protected bool $wantsResponse = true;
}
