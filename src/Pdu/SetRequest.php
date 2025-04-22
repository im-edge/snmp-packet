<?php

namespace IMEdge\Snmp\Pdu;

class SetRequest extends Pdu
{
    protected bool $wantsResponse = true;

    public function getTag(): int
    {
        return Pdu::SET_REQUEST;
    }
}
