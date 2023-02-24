<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\RtbMessage;

trait RtbMessageWriteRepositoryAwareTrait
{
    protected ?RtbMessageWriteRepository $rtbMessageWriteRepository = null;

    protected function getRtbMessageWriteRepository(): RtbMessageWriteRepository
    {
        if ($this->rtbMessageWriteRepository === null) {
            $this->rtbMessageWriteRepository = RtbMessageWriteRepository::new();
        }
        return $this->rtbMessageWriteRepository;
    }

    /**
     * @param RtbMessageWriteRepository $rtbMessageWriteRepository
     * @return static
     * @internal
     */
    public function setRtbMessageWriteRepository(RtbMessageWriteRepository $rtbMessageWriteRepository): static
    {
        $this->rtbMessageWriteRepository = $rtbMessageWriteRepository;
        return $this;
    }
}
