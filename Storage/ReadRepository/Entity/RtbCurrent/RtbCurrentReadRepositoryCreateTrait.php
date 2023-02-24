<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\RtbCurrent;

trait RtbCurrentReadRepositoryCreateTrait
{
    protected ?RtbCurrentReadRepository $rtbCurrentReadRepository = null;

    protected function createRtbCurrentReadRepository(): RtbCurrentReadRepository
    {
        return $this->rtbCurrentReadRepository ?: RtbCurrentReadRepository::new();
    }

    /**
     * @param RtbCurrentReadRepository $rtbCurrentReadRepository
     * @return static
     * @internal
     */
    public function setRtbCurrentReadRepository(RtbCurrentReadRepository $rtbCurrentReadRepository): static
    {
        $this->rtbCurrentReadRepository = $rtbCurrentReadRepository;
        return $this;
    }
}
