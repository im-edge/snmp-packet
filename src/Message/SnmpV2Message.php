<?php

namespace IMEdge\SnmpPacket\Message;

use IMEdge\SnmpPacket\SnmpVersion;

class SnmpV2Message extends SnmpV1Message
{
    public const VERSION = SnmpVersion::v2c;
}
