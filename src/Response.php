<?php

namespace IMEdge\Protocol\Snmp;

class Response extends Pdu
{
    public function getTag(): int
    {
        return Pdu::RESPONSE;
    }
}
