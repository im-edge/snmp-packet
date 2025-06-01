<?php

namespace IMEdge\Snmp\Message;

use FreeDSx\Asn1\Type\IntegerType;
use FreeDSx\Asn1\Type\OctetStringType;
use FreeDSx\Asn1\Type\SequenceType;
use IMEdge\Snmp\Error\SnmpParseError;
use IMEdge\Snmp\SecurityModel;
use IMEdge\Snmp\SnmpSecurityLevel;
use InvalidArgumentException;

use function strlen;

class Snmpv3Header
{
    protected const REPORTABLE_FLAG = "\x04";
    protected const NO_FLAG = "\x00";
    protected const SECURITY_LEVEL_FILTER = "\x03";

    final public function __construct(
        public readonly int $messageId,   // 0..2147483647
        public readonly int $maxSize = 65507,     // 484..2147483647 -> 65507?
        /**
         * msgSecurityModel
         *
         * The v3MP supports the concurrent existence of multiple Security
         * Models to provide security services for SNMPv3 messages.  The
         * msgSecurityModel field in an SNMPv3 Message identifies which Security
         * Model was used by the sender to generate the message and therefore
         * which securityModel MUST be used by the receiver to perform security
         * processing for the message.  The mapping to the appropriate
         * securityModel implementation within an SNMP engine is accomplished in
         * an implementation-dependent manner.
         */
        public readonly SnmpSecurityLevel $securityFlags = SnmpSecurityLevel::NO_AUTH_NO_PRIV,
        /**
         * see https://tools.ietf.org/html/rfc3412#section-6.4
         *
         * Whether a Report PDU MUST be sent.  It is only used in cases where the
         * PDU portion of a message cannot be decoded, due to, for example, an
         * incorrect encryption key. If the PDU can be decoded, the PDU type forms
         * the basis for decisions on sending Report PDUs.
         */
        public readonly bool $reportableFlag = false,
        public readonly SecurityModel $securityModel = SecurityModel::USM,
    ) {
    }

    public function toAsn1(): SequenceType
    {
        $flags = ($this->reportableFlag ? self::REPORTABLE_FLAG : self::NO_FLAG) | $this->securityFlags->toBinary();

        return new SequenceType(
            new IntegerType($this->messageId),
            new IntegerType($this->maxSize),
            new OctetStringType($flags),
            new IntegerType($this->securityModel->value)
        );
    }

    /**
     * @throws SnmpParseError
     */
    public static function fromAsn1(SequenceType $sequence): static
    {
        $flags = $sequence->getChild(2)?->getValue();
        if (strlen($flags) !== 1) {
            throw new InvalidArgumentException(sprintf(
                "msgFlags MUST be exactly one byte long, got %d",
                strlen($flags)
            ));
        }

        return new static(
            $sequence->getChild(0)?->getValue() ?? throw new SnmpParseError('Got no messageId'),
            $sequence->getChild(1)?->getValue() ?? throw new SnmpParseError('Got no maxSize'),
            SnmpSecurityLevel::fromBinaryFlag($flags & self::SECURITY_LEVEL_FILTER),
            ($flags & self::REPORTABLE_FLAG) === self::REPORTABLE_FLAG,
            SecurityModel::from(
                $sequence->getChild(3)?->getValue() ?? throw new SnmpParseError('Got no securityModel')
            ),
        );
        // from rfc3412#page-19:
        // msgID      INTEGER (0..2147483647),
        // msgMaxSize INTEGER (484..2147483647),
        // msgFlags   OCTET STRING (SIZE(1)),
        // --  .... ...1   authFlag
        // --  .... ..1.   privFlag
        // --  .... .1..   reportableFlag
        // --              Please observe:
        // --  .... ..00   is OK, means noAuthNoPriv
        // --  .... ..01   is OK, means authNoPriv
        // --  .... ..10   reserved, MUST NOT be used.
        // --  .... ..11   is OK, means authPriv
        // msgSecurityModel INTEGER (1..2147483647)
    }
}
