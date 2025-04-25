<?php

namespace IMEdge\Snmp\VarBindValue;

use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\OctetStringType;
use InvalidArgumentException;
use ValueError;

/**
 * IpAddress (RFC 2578 7.1.5)
 * --------------------------
 *
 * The IpAddress type represents a 32-bit internet address.  It is
 * represented as an OCTET STRING of length 4, in network byte-order.
 * Note that the IpAddress type is a tagged type for historical reasons.
 * Network addresses should be represented using an invocation of the
 * TEXTUAL-CONVENTION macro [3].
 */
class IpAddress extends ApplicationValue
{
    public const TAG = 0;
    public const NAME = 'ip_address';

    final protected function __construct(
        protected readonly string $value
    ) {
        if (strlen($value) !== 4) {
            throw new InvalidArgumentException(sprintf(
                '0x%s is not a valid IpAddress',
                bin2hex($value)
            ));
        }
    }

    public static function fromReadableValue(int|string $value): static
    {
        if (is_int($value)) {
            throw new ValueError("IP address expected, got $value");
        }
        $string = inet_pton($value);
        if ($string === false) {
            throw new ValueError("IP address expected, got $value");
        }

        return new static($string);
    }

    public function getReadableValue(): string
    {
        $readable = inet_ntop($this->value);
        if ($readable === false) { // Won't happen
            return sprintf('[ not an IP address: %s ]', $this->value);
        }

        return $readable;
    }

    public static function fromAsn1(AbstractType $type): static
    {
        if (! $type instanceof OctetStringType) {
            throw new ValueError('IpAddress requires an OctetString');
        }

        return new static($type->getValue());
    }

    public function toAsn1(): AbstractType
    {
        return Asn1::application(self::TAG, Asn1::octetString($this->value));
    }
}
