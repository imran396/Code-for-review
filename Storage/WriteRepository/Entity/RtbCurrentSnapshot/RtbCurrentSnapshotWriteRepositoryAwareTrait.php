<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\RtbCurrentSnapshot;

trait RtbCurrentSnapshotWriteRepositoryAwareTrait
{
    protected ?RtbCurrentSnapshotWriteRepository $rtbCurrentSnapshotWriteRepository = null;

    protected function getRtbCurrentSnapshotWriteRepository(): RtbCurrentSnapshotWriteRepository
    {
        if ($this->rtbCurrentSnapshotWriteRepository === null) {
            $this->rtbCurrentSnapshotWriteRepository = RtbCurrentSnapshotWriteRepository::new();
        }
        return $this->rtbCurrentSnapshotWriteRepository;
    }

    /**
     * @param RtbCurrentSnapshotWriteRepository $rtbCurrentSnapshotWriteRepository
     * @return static
     * @internal
     */
    public function setRtbCurrentSnapshotWriteRepository(RtbCurrentSnapshotWriteRepository $rtbCurrentSnapshotWriteRepository): static
    {
        $this->rtbCurrentSnapshotWriteRepository = $rtbCurrentSnapshotWriteRepository;
        return $this;
    }
}
