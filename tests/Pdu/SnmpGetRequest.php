<?php

namespace IMEdge\Tests\SnmpPacket\Pdu;

use IMEdge\SnmpPacket\Pdu\GetRequest;
use IMEdge\SnmpPacket\Message\SnmpMessage;
use IMEdge\SnmpPacket\Message\SnmpV1Message;
use IMEdge\SnmpPacket\Message\SnmpV2Message;
use IMEdge\SnmpPacket\SnmpVersion;
use PHPUnit\Framework\TestCase;

abstract class SnmpGetRequest extends TestCase
{
    protected string $hexPayload = '';
    protected ?SnmpVersion $expectedVersion = null;
    /** @var class-string */
    protected string $expectedMessageClass = TestCase::class;

    public function testParsesGetRequest(): void
    {
        $request = $this->getSysName();
        $this->assertInstanceOf($this->expectedMessageClass, $request);
        $this->assertInstanceOf(GetRequest::class, $request->getPdu());
        /** @var SnmpV2Message|SnmpV1Message $request */
        $this->assertEquals($this->expectedVersion, $request::VERSION);
    }

    public function testParsesCommunity(): void
    {
        /** @var SnmpV2Message|SnmpV1Message $request */
        $request = $this->getSysName();
        $this->assertEquals('public', $request->community);
    }

    protected function getSysName(): SnmpMessage
    {
        $hexStream = $this->hexPayload;
        return $this->parse($hexStream);
    }

    protected static function parse(string $string): SnmpMessage
    {
        $string = hex2bin($string);
        assert(is_string($string));
        return SnmpMessage::fromBinary($string);
    }
}
