<?php

namespace IMEdge\Tests\Snmp;

use IMEdge\Snmp\ErrorStatus;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ErrorStatusTest extends TestCase
{
    public function testNoErrorIsAnError(): void
    {
        $error = new ErrorStatus(0);
        $this->assertInstanceOf(ErrorStatus::class, $error);
    }

    public function testInvalidErrorNumberIsNotAccepted(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ErrorStatus(42);
    }
}
