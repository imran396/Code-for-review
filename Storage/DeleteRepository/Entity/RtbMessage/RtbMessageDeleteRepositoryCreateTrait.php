<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\RtbMessage;

trait RtbMessageDeleteRepositoryCreateTrait
{
    protected ?RtbMessageDeleteRepository $rtbMessageDeleteRepository = null;

    protected function createRtbMessageDeleteRepository(): RtbMessageDeleteRepository
    {
        return $this->rtbMessageDeleteRepository ?: RtbMessageDeleteRepository::new();
    }

    /**
     * @param RtbMessageDeleteRepository $rtbMessageDeleteRepository
     * @return static
     * @internal
     */
    public function setRtbMessageDeleteRepository(RtbMessageDeleteRepository $rtbMessageDeleteRepository): static
    {
        $this->rtbMessageDeleteRepository = $rtbMessageDeleteRepository;
        return $this;
    }
}
