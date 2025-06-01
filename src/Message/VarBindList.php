<?php

namespace IMEdge\Snmp\Message;

use FreeDSx\Asn1\Type\SequenceType;
use IMEdge\Snmp\Error\SnmpParseError;
use OutOfRangeException;

class VarBindList
{
    public function __construct(
        /** @var VarBind[] */
        public array $varBinds = []
    ) {
    }

    public function index(int $index): VarBind
    {
        return $this->varBinds[$index - 1] ?? throw new OutOfRangeException("There is no VarBind at idx=$index");
    }

    public function hasOid(string $oid): bool
    {
        foreach ($this->varBinds as $varBind) {
            if ($varBind->oid === $oid) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws SnmpParseError
     */
    public static function fromAsn1(SequenceType $sequence): VarBindList
    {
        $list = [];
        foreach ($sequence->getChildren() as $idx => $varBind) {
            if (! $varBind instanceof SequenceType) {
                throw new SnmpParseError(sprintf('VarBind at idx=%d is not a Sequence', $idx + 1));
            }
            try {
                $list[$idx] = VarBind::fromAsn1($varBind);
            } catch (SnmpParseError $e) {
                throw new SnmpParseError(sprintf(
                    "Can't decode Variable Binding %d: %s",
                    $idx + 1,
                    $e->getMessage()
                ), 0, $e);
            }
        }

        return new VarBindList($list);
    }

    public function toAsn1(): SequenceType
    {
        return new SequenceType(...array_map(fn ($varBind) => $varBind->toASN1(), $this->varBinds));
    }
}
