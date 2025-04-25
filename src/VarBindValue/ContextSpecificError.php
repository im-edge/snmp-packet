<?php

namespace IMEdge\Snmp\VarBindValue;

enum ContextSpecificError: int
{
    case NO_SUCH_OBJECT = 0;
    case NO_SUCH_INSTANCE = 1;
    case END_OF_MIB_VIEW = 2;

    public function describe(): string
    {
        return match ($this) {
            self::NO_SUCH_OBJECT   => 'No such object',
            self::NO_SUCH_INSTANCE => 'No such instance',
            self::END_OF_MIB_VIEW  => 'End of MIB view',
        };
    }
}
