<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\RtbMessage;

trait RtbMessageReadRepositoryCreateTrait
{
    protected ?RtbMessageReadRepository $rtbMessageReadRepository = null;

    protected function createRtbMessageReadRepository(): RtbMessageReadRepository
    {
        return $this->rtbMessageReadRepository ?: RtbMessageReadRepository::new();
    }

    /**
     * @param RtbMessageReadRepository $rtbMessageReadRepository
     * @return static
     * @internal
     */
    public function setRtbMessageReadRepository(RtbMessageReadRepository $rtbMessageReadRepository): static
    {
        $this->rtbMessageReadRepository = $rtbMessageReadRepository;
        return $this;
    }
}
