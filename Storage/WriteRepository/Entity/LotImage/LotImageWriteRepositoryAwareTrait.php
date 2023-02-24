<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotImage;

trait LotImageWriteRepositoryAwareTrait
{
    protected ?LotImageWriteRepository $lotImageWriteRepository = null;

    protected function getLotImageWriteRepository(): LotImageWriteRepository
    {
        if ($this->lotImageWriteRepository === null) {
            $this->lotImageWriteRepository = LotImageWriteRepository::new();
        }
        return $this->lotImageWriteRepository;
    }

    /**
     * @param LotImageWriteRepository $lotImageWriteRepository
     * @return static
     * @internal
     */
    public function setLotImageWriteRepository(LotImageWriteRepository $lotImageWriteRepository): static
    {
        $this->lotImageWriteRepository = $lotImageWriteRepository;
        return $this;
    }
}
