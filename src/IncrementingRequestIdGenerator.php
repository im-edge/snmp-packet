<?php

namespace IMEdge\SnmpPacket;

class IncrementingRequestIdGenerator
{
    protected int $lastId = 0;
    public function getNextId(): int
    {
        return ++$this->lastId;
    }
}
