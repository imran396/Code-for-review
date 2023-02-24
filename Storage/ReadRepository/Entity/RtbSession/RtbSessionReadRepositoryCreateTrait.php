<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\RtbSession;

trait RtbSessionReadRepositoryCreateTrait
{
    protected ?RtbSessionReadRepository $rtbSessionReadRepository = null;

    protected function createRtbSessionReadRepository(): RtbSessionReadRepository
    {
        return $this->rtbSessionReadRepository ?: RtbSessionReadRepository::new();
    }

    /**
     * @param RtbSessionReadRepository $rtbSessionReadRepository
     * @return static
     * @internal
     */
    public function setRtbSessionReadRepository(RtbSessionReadRepository $rtbSessionReadRepository): static
    {
        $this->rtbSessionReadRepository = $rtbSessionReadRepository;
        return $this;
    }
}
