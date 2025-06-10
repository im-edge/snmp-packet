<?php

namespace IMEdge\SnmpPacket;

enum SnmpVersion: int
{
    case v1  = 0;
    case v2c = 1;
    case v3  = 3;
}
