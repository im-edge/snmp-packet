<?php

namespace IMEdge\Tests\SnmpPacket\Pdu;

use IMEdge\SnmpPacket\Message\SnmpV1Message;
use IMEdge\SnmpPacket\SnmpVersion;

class SnmpGetRequestV1Test extends SnmpGetRequest
{
    protected string $hexPayload = '302902010004067075626c6963a01c02046b1e393d020100020100300e300c06082b060102010105'
        . '000500';
    protected ?SnmpVersion $expectedVersion = SnmpVersion::v1;
    protected string $expectedMessageClass = SnmpV1Message::class;
}
