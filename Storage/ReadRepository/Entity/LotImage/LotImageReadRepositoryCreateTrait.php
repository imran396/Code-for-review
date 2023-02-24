<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\LotImage;

trait LotImageReadRepositoryCreateTrait
{
    protected ?LotImageReadRepository $lotImageReadRepository = null;

    protected function createLotImageReadRepository(): LotImageReadRepository
    {
        return $this->lotImageReadRepository ?: LotImageReadRepository::new();
    }

    /**
     * @param LotImageReadRepository $lotImageReadRepository
     * @return static
     * @internal
     */
    public function setLotImageReadRepository(LotImageReadRepository $lotImageReadRepository): static
    {
        $this->lotImageReadRepository = $lotImageReadRepository;
        return $this;
    }
}
