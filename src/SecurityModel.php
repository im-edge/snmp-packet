<?php

namespace IMEdge\SnmpPacket;

enum SecurityModel: int
{
    case SNMPv1 = 1;
    case SNMPv2c = 2;
    case USM = 3;
}
