<?php

namespace IMEdge\Snmp\VarBindValue;

use FreeDSx\Asn1\Encoder\BerEncoder;
use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Asn1\Type\IncompleteType;
use FreeDSx\Asn1\Type\OctetStringType;
use InvalidArgumentException;
use ValueError;

class Value
{
    protected static BerEncoder $encoder;

    /*
     * SNMPv2-SMI - https://datatracker.ietf.org/doc/html/rfc2578:
     *
     * -- the "base types" defined here are:
     * --   3 built-in ASN.1 types: INTEGER, OCTET STRING, OBJECT IDENTIFIER
     * --   8 application-defined types: Integer32, IpAddress, Counter32,
     * --              Gauge32, Unsigned32, TimeTicks, Opaque, and Counter64
     *
     * IpAddress ::= [APPLICATION 0] IMPLICIT OCTET STRING (SIZE (4))
     *
     * -- this wraps
     * Counter32 ::= [APPLICATION 1] IMPLICIT INTEGER (0..4294967295)
     *
     * -- this doesn't wrap
     * Gauge32 ::= [APPLICATION 2] IMPLICIT INTEGER (0..4294967295)
     *
     * -- an unsigned 32-bit quantity
     * -- indistinguishable from Gauge32
     * Unsigned32 ::= [APPLICATION 2] IMPLICIT INTEGER (0..4294967295)
     *
     * -- hundredths of seconds since an epoch
     * TimeTicks ::= [APPLICATION 3] IMPLICIT INTEGER (0..4294967295)
     *
     * -- for backward-compatibility only
     * Opaque ::= [APPLICATION 4] IMPLICIT OCTET STRING
     *
     * -- for counters that wrap in less than one hour with only 32 bits
     * Counter64 ::= [APPLICATION 6] IMPLICIT INTEGER (0..18446744073709551615)
     */
    public static function fromAsn1(AbstractType $type): VarBindValue
    {
        $class = $type->getTagClass();
        return match ($class) {
            AbstractType::TAG_CLASS_UNIVERSAL => self::fromUniversal($type),
            AbstractType::TAG_CLASS_APPLICATION => self::createApplicationValue($type),
            AbstractType::TAG_CLASS_CONTEXT_SPECIFIC => ContextSpecific::fromAsn1($type),
            default => throw new InvalidArgumentException(
                sprintf(
                    'Unsupported ASN1 class=%s (%s)',
                    $class,
                    get_class($type)
                )
            ),
        };
    }

    public static function createApplicationValue(AbstractType $type): ApplicationValue
    {
        if (! $type instanceof IncompleteType) {
            throw new ValueError(sprintf('IncompleteType expected, got ' . get_class($type)));
        }
        $encoder = self::$encoder ??= new BerEncoder();
        assert(is_int($type->getTagNumber()));
        return match ($type->getTagNumber()) {
            IpAddress::TAG   => IpAddress::fromAsn1($encoder->complete($type, AbstractType::TAG_TYPE_OCTET_STRING)),
            Counter32::TAG   => Counter32::fromAsn1($encoder->complete($type, AbstractType::TAG_TYPE_INTEGER)),
            Gauge32::TAG     => Gauge32::fromAsn1($encoder->complete($type, AbstractType::TAG_TYPE_INTEGER)),
            TimeTicks::TAG   => TimeTicks::fromAsn1($encoder->complete($type, AbstractType::TAG_TYPE_INTEGER)),
            Opaque::TAG      => Opaque::fromAsn1($encoder->complete($type, AbstractType::TAG_TYPE_OCTET_STRING)),
            NsapAddress::TAG => NsapAddress::fromAsn1($encoder->complete($type, AbstractType::TAG_TYPE_OCTET_STRING)),
            Counter64::TAG   => Counter64::fromAsn1($encoder->complete($type, AbstractType::TAG_TYPE_INTEGER)),
            Unsigned32::TAG  => Unsigned32::fromAsn1($encoder->complete($type, AbstractType::TAG_TYPE_INTEGER)),
            default => throw new ValueError(
                'Unknown application data type, tag=' . $type->getTagNumber()
            ),
        };
    }

    protected static function fromUniversal(AbstractType $type): VarBindValue
    {
        $tag = $type->getTagNumber();
        return match ($tag) {
            AbstractType::TAG_TYPE_INTEGER      => Integer32::fromAsn1($type),
            AbstractType::TAG_TYPE_OCTET_STRING => OctetString::fromAsn1($type),
            AbstractType::TAG_TYPE_BIT_STRING   => BitString::fromAsn1($type),
            AbstractType::TAG_TYPE_OID          => ObjectIdentifier::fromAsn1($type),
            AbstractType::TAG_TYPE_NULL => new NullValue(),
            default => throw new ValueError(
                sprintf(
                    "SNMP does not support ASN1 Universal type with tag='%s'",
                    is_int($tag) ? dechex($tag) : $tag
                )
            ),
        };
    }
}
