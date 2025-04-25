<?php

namespace IMEdge\Snmp\VarBindValue;

/**
 * Counter32 (RFC 2578 7.1.6)
 * --------------------------
 *
 * The Counter32 type represents a non-negative integer which
 * monotonically increases until it reaches a maximum value of 2^32-1
 * (4294967295 decimal), when it wraps around and starts increasing
 * again from zero.
 *
 * Counters have no defined "initial" value, and thus, a single value of
 * a Counter has (in general) no information content.  Discontinuities
 * in the monotonically increasing value normally occur at re-
 * initialization of the management system, and at other times as
 * specified in the description of an object-type using this ASN.1 type.
 * If such other times can occur, for example, the creation of an object
 * instance at times other than re-initialization, then a corresponding
 * object should be defined, with an appropriate SYNTAX clause, to
 * indicate the last discontinuity.  Examples of appropriate SYNTAX
 * clause include:  TimeStamp (a textual convention defined in [3]),
 * DateAndTime (another textual convention from [3]) or TimeTicks.
 *
 * The value of the MAX-ACCESS clause for objects with a SYNTAX clause
 * value of Counter32 is either "read-only" or "accessible-for-notify".
 *
 * A DEFVAL clause is not allowed for objects with a SYNTAX clause value
 * of Counter32.
 */
class Counter32 extends Unsigned32
{
    public const TAG = 1;
    public const NAME = 'counter32';
}
