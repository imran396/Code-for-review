<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\RtbCurrentIncrement;

trait RtbCurrentIncrementReadRepositoryCreateTrait
{
    protected ?RtbCurrentIncrementReadRepository $rtbCurrentIncrementReadRepository = null;

    protected function createRtbCurrentIncrementReadRepository(): RtbCurrentIncrementReadRepository
    {
        return $this->rtbCurrentIncrementReadRepository ?: RtbCurrentIncrementReadRepository::new();
    }

    /**
     * @param RtbCurrentIncrementReadRepository $rtbCurrentIncrementReadRepository
     * @return static
     * @internal
     */
    public function setRtbCurrentIncrementReadRepository(RtbCurrentIncrementReadRepository $rtbCurrentIncrementReadRepository): static
    {
        $this->rtbCurrentIncrementReadRepository = $rtbCurrentIncrementReadRepository;
        return $this;
    }
}
