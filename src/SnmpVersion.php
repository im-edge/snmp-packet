<?php

namespace IMEdge\Snmp;

enum SnmpVersion: int
{
    case v1  = 0;
    case v2c = 1;
    case v3  = 3;
}
