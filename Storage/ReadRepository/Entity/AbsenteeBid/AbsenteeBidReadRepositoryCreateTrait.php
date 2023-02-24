<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AbsenteeBid;

trait AbsenteeBidReadRepositoryCreateTrait
{
    protected ?AbsenteeBidReadRepository $absenteeBidReadRepository = null;

    protected function createAbsenteeBidReadRepository(): AbsenteeBidReadRepository
    {
        return $this->absenteeBidReadRepository ?: AbsenteeBidReadRepository::new();
    }

    /**
     * @param AbsenteeBidReadRepository $absenteeBidReadRepository
     * @return static
     * @internal
     */
    public function setAbsenteeBidReadRepository(AbsenteeBidReadRepository $absenteeBidReadRepository): static
    {
        $this->absenteeBidReadRepository = $absenteeBidReadRepository;
        return $this;
    }
}
