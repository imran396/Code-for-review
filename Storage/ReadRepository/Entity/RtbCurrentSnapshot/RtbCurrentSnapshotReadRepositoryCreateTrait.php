<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\RtbCurrentSnapshot;

trait RtbCurrentSnapshotReadRepositoryCreateTrait
{
    protected ?RtbCurrentSnapshotReadRepository $rtbCurrentSnapshotReadRepository = null;

    protected function createRtbCurrentSnapshotReadRepository(): RtbCurrentSnapshotReadRepository
    {
        return $this->rtbCurrentSnapshotReadRepository ?: RtbCurrentSnapshotReadRepository::new();
    }

    /**
     * @param RtbCurrentSnapshotReadRepository $rtbCurrentSnapshotReadRepository
     * @return static
     * @internal
     */
    public function setRtbCurrentSnapshotReadRepository(RtbCurrentSnapshotReadRepository $rtbCurrentSnapshotReadRepository): static
    {
        $this->rtbCurrentSnapshotReadRepository = $rtbCurrentSnapshotReadRepository;
        return $this;
    }
}
