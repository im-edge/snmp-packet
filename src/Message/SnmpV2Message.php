<?php

namespace IMEdge\Snmp\Message;

use IMEdge\Snmp\SnmpVersion;

class SnmpV2Message extends SnmpV1Message
{
    public const VERSION = SnmpVersion::v2c;
}
