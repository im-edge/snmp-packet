<?php

namespace IMEdge\Snmp\VarBindValue;

use FreeDSx\Asn1\Asn1;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\OctetStringType;
use InvalidArgumentException;
use ValueError;

class NsapAddress extends ApplicationValue
{
    public const TAG = 5;
    public const NAME = 'nsap_address';

    final public function __construct(
        protected string $value
    ) {
        if (strlen($value) > 20) {
            throw new InvalidArgumentException(sprintf(
                '0x%s is not a valid NSAP Address',
                bin2hex($value)
            ));
        }
    }

    public function getReadableValue(): string
    {
        return JsonHelper::stringForJson($this->value);
    }

    public static function fromAsn1(AbstractType $type): static
    {
        if (! $type instanceof OctetStringType) {
            throw new ValueError('NSAP address requires an OctetString');
        }

        return new static($type->getValue());
    }

    public function toAsn1(): AbstractType
    {
        return Asn1::application(self::TAG, Asn1::octetString($this->value));
    }

    public static function fromReadableValue(int|string $value): static
    {
        if (is_int($value)) {
            throw new ValueError('NSAP address needs a string');
        }

        return new static(JsonHelper::stringFromJson($value));
    }
}
