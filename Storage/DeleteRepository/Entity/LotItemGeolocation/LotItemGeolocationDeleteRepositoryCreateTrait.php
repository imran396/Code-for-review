<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LotItemGeolocation;

trait LotItemGeolocationDeleteRepositoryCreateTrait
{
    protected ?LotItemGeolocationDeleteRepository $lotItemGeolocationDeleteRepository = null;

    protected function createLotItemGeolocationDeleteRepository(): LotItemGeolocationDeleteRepository
    {
        return $this->lotItemGeolocationDeleteRepository ?: LotItemGeolocationDeleteRepository::new();
    }

    /**
     * @param LotItemGeolocationDeleteRepository $lotItemGeolocationDeleteRepository
     * @return static
     * @internal
     */
    public function setLotItemGeolocationDeleteRepository(LotItemGeolocationDeleteRepository $lotItemGeolocationDeleteRepository): static
    {
        $this->lotItemGeolocationDeleteRepository = $lotItemGeolocationDeleteRepository;
        return $this;
    }
}
