<?php

namespace IMEdge\SnmpPacket\Pdu;

class GetNextRequest extends Pdu
{
    public const TAG = 1;

    protected bool $wantsResponse = true;
}
