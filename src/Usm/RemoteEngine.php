<?php

namespace IMEdge\SnmpPacket\Usm;

class RemoteEngine
{
    public function __construct(
        /** @readonly from external */
        public string $id = '',
        /** @readonlyfrom external */
        public int $boots = 0,
        /** @readonlyfrom external */
        public int $time = 0,
    ) {
    }

    public function hasId(): bool
    {
        return $this->id !== '';
    }

    public function refresh(UserBasedSecurityModel $securityParameters): bool
    {
        $changed = false;
        if ($this->id !== $securityParameters->engineId) {
            $this->id = $securityParameters->engineId;
            $changed = true;
        }
        if ($this->time !== $securityParameters->engineTime) {
            $this->time = $securityParameters->engineTime;
            $changed = true;
        }
        if ($this->boots !== $securityParameters->engineBoots) {
            $this->boots = $securityParameters->engineBoots;
            $changed = true;
        }

        return $changed;
    }
}
