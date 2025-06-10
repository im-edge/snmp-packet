<?php

namespace IMEdge\SnmpPacket\VarBindValue;

/**
 * TimeTicks (RFC 2578 7.1.8)
 * --------------------------
 *
 * The TimeTicks type represents a non-negative integer which represents
 * the time, modulo 2^32 (4294967296 decimal), in hundredths of a second
 * between two epochs.  When objects are defined which use this ASN.1
 * type, the description of the object identifies both of the reference
 * epochs.
 *
 * For example, [3] defines the TimeStamp textual convention which is
 * based on the TimeTicks type.  With a TimeStamp, the first reference
 * epoch is defined as the time when sysUpTime [5] was zero, and the
 * second reference epoch is defined as the current value of sysUpTime.
 *
 * The TimeTicks type may not be sub-typed.
 */
class TimeTicks extends Unsigned32
{
    public const TAG = 3;
    public const NAME = 'time_ticks';
}
