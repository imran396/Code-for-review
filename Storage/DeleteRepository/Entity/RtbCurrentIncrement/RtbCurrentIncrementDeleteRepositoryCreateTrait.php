<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\RtbCurrentIncrement;

trait RtbCurrentIncrementDeleteRepositoryCreateTrait
{
    protected ?RtbCurrentIncrementDeleteRepository $rtbCurrentIncrementDeleteRepository = null;

    protected function createRtbCurrentIncrementDeleteRepository(): RtbCurrentIncrementDeleteRepository
    {
        return $this->rtbCurrentIncrementDeleteRepository ?: RtbCurrentIncrementDeleteRepository::new();
    }

    /**
     * @param RtbCurrentIncrementDeleteRepository $rtbCurrentIncrementDeleteRepository
     * @return static
     * @internal
     */
    public function setRtbCurrentIncrementDeleteRepository(RtbCurrentIncrementDeleteRepository $rtbCurrentIncrementDeleteRepository): static
    {
        $this->rtbCurrentIncrementDeleteRepository = $rtbCurrentIncrementDeleteRepository;
        return $this;
    }
}
