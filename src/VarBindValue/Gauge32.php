<?php

namespace IMEdge\SnmpPacket\VarBindValue;

/**
 * Gauge32 (RFC 2578 7.1.7)
 * ------------------------
 *
 * The Gauge32 type represents a non-negative integer, which may
 * increase or decrease, but shall never exceed a maximum value, nor
 * fall below a minimum value.  The maximum value can not be greater
 * than 2^32-1 (4294967295 decimal), and the minimum value can not be
 * smaller than 0.  The value of a Gauge32 has its maximum value
 * whenever the information being modeled is greater than or equal to
 * its maximum value, and has its minimum value whenever the information
 * being modeled is smaller than or equal to its minimum value.  If the
 * information being modeled subsequently decreases below (increases
 * above) the maximum (minimum) value, the Gauge32 also decreases
 * (increases).  (Note that despite of the use of the term "latched" in
 * the original definition of this type, it does not become "stuck" at
 * its maximum or minimum value.)
 */
class Gauge32 extends Unsigned32
{
    public const TAG = 2;
    public const NAME = 'gauge32';
}
