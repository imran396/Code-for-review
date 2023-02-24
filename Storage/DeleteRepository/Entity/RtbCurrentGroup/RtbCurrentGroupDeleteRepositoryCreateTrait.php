<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\RtbCurrentGroup;

trait RtbCurrentGroupDeleteRepositoryCreateTrait
{
    protected ?RtbCurrentGroupDeleteRepository $rtbCurrentGroupDeleteRepository = null;

    protected function createRtbCurrentGroupDeleteRepository(): RtbCurrentGroupDeleteRepository
    {
        return $this->rtbCurrentGroupDeleteRepository ?: RtbCurrentGroupDeleteRepository::new();
    }

    /**
     * @param RtbCurrentGroupDeleteRepository $rtbCurrentGroupDeleteRepository
     * @return static
     * @internal
     */
    public function setRtbCurrentGroupDeleteRepository(RtbCurrentGroupDeleteRepository $rtbCurrentGroupDeleteRepository): static
    {
        $this->rtbCurrentGroupDeleteRepository = $rtbCurrentGroupDeleteRepository;
        return $this;
    }
}
