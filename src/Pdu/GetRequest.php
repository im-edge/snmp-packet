<?php

namespace IMEdge\SnmpPacket\Pdu;

class GetRequest extends Pdu
{
    public const TAG = 0;
    protected bool $wantsResponse = true;
}
