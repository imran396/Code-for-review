<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AbsenteeBid;

trait AbsenteeBidWriteRepositoryAwareTrait
{
    protected ?AbsenteeBidWriteRepository $absenteeBidWriteRepository = null;

    protected function getAbsenteeBidWriteRepository(): AbsenteeBidWriteRepository
    {
        if ($this->absenteeBidWriteRepository === null) {
            $this->absenteeBidWriteRepository = AbsenteeBidWriteRepository::new();
        }
        return $this->absenteeBidWriteRepository;
    }

    /**
     * @param AbsenteeBidWriteRepository $absenteeBidWriteRepository
     * @return static
     * @internal
     */
    public function setAbsenteeBidWriteRepository(AbsenteeBidWriteRepository $absenteeBidWriteRepository): static
    {
        $this->absenteeBidWriteRepository = $absenteeBidWriteRepository;
        return $this;
    }
}
