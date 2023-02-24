<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\LotItemGeolocation;

trait LotItemGeolocationReadRepositoryCreateTrait
{
    protected ?LotItemGeolocationReadRepository $lotItemGeolocationReadRepository = null;

    protected function createLotItemGeolocationReadRepository(): LotItemGeolocationReadRepository
    {
        return $this->lotItemGeolocationReadRepository ?: LotItemGeolocationReadRepository::new();
    }

    /**
     * @param LotItemGeolocationReadRepository $lotItemGeolocationReadRepository
     * @return static
     * @internal
     */
    public function setLotItemGeolocationReadRepository(LotItemGeolocationReadRepository $lotItemGeolocationReadRepository): static
    {
        $this->lotItemGeolocationReadRepository = $lotItemGeolocationReadRepository;
        return $this;
    }
}
