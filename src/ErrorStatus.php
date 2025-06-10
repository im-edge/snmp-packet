<?php

namespace IMEdge\SnmpPacket;

enum ErrorStatus: int
{
    // 0-5 are available since v1:
    case NO_ERROR = 0;
    case TOO_BIG = 1;
    // Hint: noSuchName, badValue and readOnly are here for proxy compatibility
    case NO_SUCH_NAME = 2;
    case BAD_VALUE = 3;
    case READ_ONLY = 4;
    case GEN_ERR = 5;

    // available since v2:
    case NO_ACCESS = 6;
    case WRONG_TYPE = 7;
    case WRONG_LENGTH = 8;
    case WRONG_ENCODING = 9;
    case WRONG_VALUE = 10;
    case NO_CREATION = 11;
    case INCONSISTENT_VALUE = 12;
    case RESOURCE_UNAVAILABLE = 13;
    case COMMIT_FAILED = 14;
    case UNDO_FAILED = 15;
    case AUTHORIZATION_ERROR = 16;
    case NOT_WRITABLE = 17;
    case INCONSISTENT_NAME = 18;

    public function getStatus(): int
    {
        return $this->value;
    }

    public function isError(): bool
    {
        return $this !== ErrorStatus::NO_ERROR;
    }

    public function getStatusName(): string
    {
        return match ($this) {
            self::NO_ERROR => 'noError',
            self::TOO_BIG => 'tooBig',
            self::NO_SUCH_NAME => 'noSuchName',
            self::BAD_VALUE => 'badValue',
            self::READ_ONLY => 'readOnly',
            self::GEN_ERR => 'genErr',
            self::NO_ACCESS => 'noAccess',
            self::WRONG_TYPE => 'wrongType',
            self::WRONG_LENGTH => 'wrongLength',
            self::WRONG_ENCODING => 'wrongEncoding',
            self::WRONG_VALUE => 'wrongValue',
            self::NO_CREATION => 'noCreation',
            self::INCONSISTENT_VALUE => 'inconsistentValue',
            self::RESOURCE_UNAVAILABLE => 'resourceUnavailable',
            self::COMMIT_FAILED => 'commitFailed',
            self::UNDO_FAILED => 'undoFailed',
            self::AUTHORIZATION_ERROR => 'authorizationError',
            self::NOT_WRITABLE => 'notWritable',
            self::INCONSISTENT_NAME => 'inconsistentName',
        };
    }
}
