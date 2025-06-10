<?php

namespace IMEdge\Tests\SnmpPacket;

use IMEdge\SnmpPacket\ErrorStatus;
use PHPUnit\Framework\TestCase;
use ValueError;

class ErrorStatusTest extends TestCase
{
    public function testNoErrorIsAnError(): void
    {
        $error = ErrorStatus::from(0);
        $this->assertInstanceOf(ErrorStatus::class, $error);
    }

    public function testInvalidErrorNumberIsNotAccepted(): void
    {
        $this->expectException(ValueError::class);
        ErrorStatus::from(42);
    }
}
