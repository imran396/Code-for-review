<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\RtbCurrentSnapshot;

trait RtbCurrentSnapshotDeleteRepositoryCreateTrait
{
    protected ?RtbCurrentSnapshotDeleteRepository $rtbCurrentSnapshotDeleteRepository = null;

    protected function createRtbCurrentSnapshotDeleteRepository(): RtbCurrentSnapshotDeleteRepository
    {
        return $this->rtbCurrentSnapshotDeleteRepository ?: RtbCurrentSnapshotDeleteRepository::new();
    }

    /**
     * @param RtbCurrentSnapshotDeleteRepository $rtbCurrentSnapshotDeleteRepository
     * @return static
     * @internal
     */
    public function setRtbCurrentSnapshotDeleteRepository(RtbCurrentSnapshotDeleteRepository $rtbCurrentSnapshotDeleteRepository): static
    {
        $this->rtbCurrentSnapshotDeleteRepository = $rtbCurrentSnapshotDeleteRepository;
        return $this;
    }
}
