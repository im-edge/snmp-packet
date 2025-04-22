<?php

namespace IMEdge\Snmp\Pdu;

class Response extends Pdu
{
    public function getTag(): int
    {
        return Pdu::RESPONSE;
    }
}
