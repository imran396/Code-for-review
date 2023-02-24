<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LotImage;

trait LotImageDeleteRepositoryCreateTrait
{
    protected ?LotImageDeleteRepository $lotImageDeleteRepository = null;

    protected function createLotImageDeleteRepository(): LotImageDeleteRepository
    {
        return $this->lotImageDeleteRepository ?: LotImageDeleteRepository::new();
    }

    /**
     * @param LotImageDeleteRepository $lotImageDeleteRepository
     * @return static
     * @internal
     */
    public function setLotImageDeleteRepository(LotImageDeleteRepository $lotImageDeleteRepository): static
    {
        $this->lotImageDeleteRepository = $lotImageDeleteRepository;
        return $this;
    }
}
