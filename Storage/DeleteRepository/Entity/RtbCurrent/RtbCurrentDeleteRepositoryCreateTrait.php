<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\RtbCurrent;

trait RtbCurrentDeleteRepositoryCreateTrait
{
    protected ?RtbCurrentDeleteRepository $rtbCurrentDeleteRepository = null;

    protected function createRtbCurrentDeleteRepository(): RtbCurrentDeleteRepository
    {
        return $this->rtbCurrentDeleteRepository ?: RtbCurrentDeleteRepository::new();
    }

    /**
     * @param RtbCurrentDeleteRepository $rtbCurrentDeleteRepository
     * @return static
     * @internal
     */
    public function setRtbCurrentDeleteRepository(RtbCurrentDeleteRepository $rtbCurrentDeleteRepository): static
    {
        $this->rtbCurrentDeleteRepository = $rtbCurrentDeleteRepository;
        return $this;
    }
}
