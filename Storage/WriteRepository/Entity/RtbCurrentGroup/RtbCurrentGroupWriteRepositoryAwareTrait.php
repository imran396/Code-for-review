<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\RtbCurrentGroup;

trait RtbCurrentGroupWriteRepositoryAwareTrait
{
    protected ?RtbCurrentGroupWriteRepository $rtbCurrentGroupWriteRepository = null;

    protected function getRtbCurrentGroupWriteRepository(): RtbCurrentGroupWriteRepository
    {
        if ($this->rtbCurrentGroupWriteRepository === null) {
            $this->rtbCurrentGroupWriteRepository = RtbCurrentGroupWriteRepository::new();
        }
        return $this->rtbCurrentGroupWriteRepository;
    }

    /**
     * @param RtbCurrentGroupWriteRepository $rtbCurrentGroupWriteRepository
     * @return static
     * @internal
     */
    public function setRtbCurrentGroupWriteRepository(RtbCurrentGroupWriteRepository $rtbCurrentGroupWriteRepository): static
    {
        $this->rtbCurrentGroupWriteRepository = $rtbCurrentGroupWriteRepository;
        return $this;
    }
}
