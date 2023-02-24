<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\RtbSession;

trait RtbSessionDeleteRepositoryCreateTrait
{
    protected ?RtbSessionDeleteRepository $rtbSessionDeleteRepository = null;

    protected function createRtbSessionDeleteRepository(): RtbSessionDeleteRepository
    {
        return $this->rtbSessionDeleteRepository ?: RtbSessionDeleteRepository::new();
    }

    /**
     * @param RtbSessionDeleteRepository $rtbSessionDeleteRepository
     * @return static
     * @internal
     */
    public function setRtbSessionDeleteRepository(RtbSessionDeleteRepository $rtbSessionDeleteRepository): static
    {
        $this->rtbSessionDeleteRepository = $rtbSessionDeleteRepository;
        return $this;
    }
}
