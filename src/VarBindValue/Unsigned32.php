<?php

namespace IMEdge\Snmp\VarBindValue;

use ValueError;

use function ctype_digit;
use function is_string;
use function sprintf;

/**
 * Unsigned32 (RFC 2578 7.1.11)
 * ----------------------------
 *
 * The Unsigned32 type represents integer-valued information between 0
 * and 2^32-1 inclusive (0 to 4294967295 decimal).
 */
class Unsigned32 extends ApplicationValue
{
    use ApplicationSerializationInteger;

    public const TAG = 7;
    public const NAME = 'unsigned32';

    final public function __construct(
        protected readonly int|string $value
    ) {
        if (is_string($value)) {
            if (! ctype_digit($value)) {
                throw new ValueError("Number expected, got $value");
            }
            $value = (int) $value;
        }
        if ($value < 0 || $value > 4294967295) {
            throw new ValueError(sprintf(
                '%s is not a valid unsigned integer',
                $value
            ));
        }
    }
}
