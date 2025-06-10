<?php

namespace IMEdge\SnmpPacket;

interface RequestIdConsumer
{
    public function hasId(int $id): bool;
}
