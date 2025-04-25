<?php

namespace IMEdge\Snmp\VarBindValue;

use ValueError;

/**
 * Counter64 (RFC 2578 7.1.10)
 * ---------------------------
 *
 * The Counter64 type represents a non-negative integer which
 * monotonically increases until it reaches a maximum value of 2^64-1
 * (18446744073709551615 decimal), when it wraps around and starts
 * increasing again from zero.
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
 * clause are:  TimeStamp (a textual convention defined in [3]),
 * DateAndTime (another textual convention from [3]) or TimeTicks.
 *
 * The value of the MAX-ACCESS clause for objects with a SYNTAX clause
 * value of Counter64 is either "read-only" or "accessible-for-notify".
 *
 * A requirement on "standard" MIB modules is that the Counter64 type
 * may be used only if the information being modeled would wrap in less
 * than one hour if the Counter32 type was used instead.
 *
 * A DEFVAL clause is not allowed for objects with a SYNTAX clause value
 * of Counter64.
 */
class Counter64 extends ApplicationValue
{
    use ApplicationSerializationInteger;

    public const TAG = 6;
    public const NAME = 'counter64';

    final public function __construct(
        protected readonly int|string $value
    ) {
        if (is_string($value)) {
            if (! ctype_digit($value)) {
                throw new ValueError("Number expected, got $value");
            }
            // TODO: check for 18446744073709551615 or negative sign
        }
    }
}
