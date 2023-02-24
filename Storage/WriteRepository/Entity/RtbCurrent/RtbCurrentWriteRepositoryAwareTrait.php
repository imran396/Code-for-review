<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\RtbCurrent;

trait RtbCurrentWriteRepositoryAwareTrait
{
    protected ?RtbCurrentWriteRepository $rtbCurrentWriteRepository = null;

    protected function getRtbCurrentWriteRepository(): RtbCurrentWriteRepository
    {
        if ($this->rtbCurrentWriteRepository === null) {
            $this->rtbCurrentWriteRepository = RtbCurrentWriteRepository::new();
        }
        return $this->rtbCurrentWriteRepository;
    }

    /**
     * @param RtbCurrentWriteRepository $rtbCurrentWriteRepository
     * @return static
     * @internal
     */
    public function setRtbCurrentWriteRepository(RtbCurrentWriteRepository $rtbCurrentWriteRepository): static
    {
        $this->rtbCurrentWriteRepository = $rtbCurrentWriteRepository;
        return $this;
    }
}
