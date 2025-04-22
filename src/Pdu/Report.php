<?php

namespace IMEdge\Snmp\Pdu;

/**
 * Report
 *
 * Has been added in SNMPv3
 */
class Report extends Pdu
{
    protected bool $wantsResponse = true;

    public function getTag(): int
    {
        return Pdu::REPORT;
    }
}
