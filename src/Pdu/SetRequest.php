<?php

namespace IMEdge\Snmp\Pdu;

class SetRequest extends Pdu
{
    public const TAG = 3;
    protected bool $wantsResponse = true;
}
