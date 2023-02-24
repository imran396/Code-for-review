<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\RtbCurrentIncrement;

trait RtbCurrentIncrementWriteRepositoryAwareTrait
{
    protected ?RtbCurrentIncrementWriteRepository $rtbCurrentIncrementWriteRepository = null;

    protected function getRtbCurrentIncrementWriteRepository(): RtbCurrentIncrementWriteRepository
    {
        if ($this->rtbCurrentIncrementWriteRepository === null) {
            $this->rtbCurrentIncrementWriteRepository = RtbCurrentIncrementWriteRepository::new();
        }
        return $this->rtbCurrentIncrementWriteRepository;
    }

    /**
     * @param RtbCurrentIncrementWriteRepository $rtbCurrentIncrementWriteRepository
     * @return static
     * @internal
     */
    public function setRtbCurrentIncrementWriteRepository(RtbCurrentIncrementWriteRepository $rtbCurrentIncrementWriteRepository): static
    {
        $this->rtbCurrentIncrementWriteRepository = $rtbCurrentIncrementWriteRepository;
        return $this;
    }
}
