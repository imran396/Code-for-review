<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\RtbCurrentGroup;

trait RtbCurrentGroupReadRepositoryCreateTrait
{
    protected ?RtbCurrentGroupReadRepository $rtbCurrentGroupReadRepository = null;

    protected function createRtbCurrentGroupReadRepository(): RtbCurrentGroupReadRepository
    {
        return $this->rtbCurrentGroupReadRepository ?: RtbCurrentGroupReadRepository::new();
    }

    /**
     * @param RtbCurrentGroupReadRepository $rtbCurrentGroupReadRepository
     * @return static
     * @internal
     */
    public function setRtbCurrentGroupReadRepository(RtbCurrentGroupReadRepository $rtbCurrentGroupReadRepository): static
    {
        $this->rtbCurrentGroupReadRepository = $rtbCurrentGroupReadRepository;
        return $this;
    }
}
