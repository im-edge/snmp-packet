<?php

namespace IMEdge\SnmpPacket\Pdu;

/**
 * Report
 *
 * Has been added in SNMPv3
 */
class Report extends Pdu
{
    public const TAG = 8;
    protected bool $wantsResponse = true;
}
