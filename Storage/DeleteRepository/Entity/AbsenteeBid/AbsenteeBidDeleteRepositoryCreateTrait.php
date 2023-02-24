<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AbsenteeBid;

trait AbsenteeBidDeleteRepositoryCreateTrait
{
    protected ?AbsenteeBidDeleteRepository $absenteeBidDeleteRepository = null;

    protected function createAbsenteeBidDeleteRepository(): AbsenteeBidDeleteRepository
    {
        return $this->absenteeBidDeleteRepository ?: AbsenteeBidDeleteRepository::new();
    }

    /**
     * @param AbsenteeBidDeleteRepository $absenteeBidDeleteRepository
     * @return static
     * @internal
     */
    public function setAbsenteeBidDeleteRepository(AbsenteeBidDeleteRepository $absenteeBidDeleteRepository): static
    {
        $this->absenteeBidDeleteRepository = $absenteeBidDeleteRepository;
        return $this;
    }
}
