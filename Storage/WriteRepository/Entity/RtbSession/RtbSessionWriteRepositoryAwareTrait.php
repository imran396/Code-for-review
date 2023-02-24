<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\RtbSession;

trait RtbSessionWriteRepositoryAwareTrait
{
    protected ?RtbSessionWriteRepository $rtbSessionWriteRepository = null;

    protected function getRtbSessionWriteRepository(): RtbSessionWriteRepository
    {
        if ($this->rtbSessionWriteRepository === null) {
            $this->rtbSessionWriteRepository = RtbSessionWriteRepository::new();
        }
        return $this->rtbSessionWriteRepository;
    }

    /**
     * @param RtbSessionWriteRepository $rtbSessionWriteRepository
     * @return static
     * @internal
     */
    public function setRtbSessionWriteRepository(RtbSessionWriteRepository $rtbSessionWriteRepository): static
    {
        $this->rtbSessionWriteRepository = $rtbSessionWriteRepository;
        return $this;
    }
}
