<?php

namespace IMEdge\Protocol\Snmp;

interface RequestIdConsumer
{
    public function hasId(int $id): bool;
}
