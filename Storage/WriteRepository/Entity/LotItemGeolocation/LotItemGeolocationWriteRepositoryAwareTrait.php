<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotItemGeolocation;

trait LotItemGeolocationWriteRepositoryAwareTrait
{
    protected ?LotItemGeolocationWriteRepository $lotItemGeolocationWriteRepository = null;

    protected function getLotItemGeolocationWriteRepository(): LotItemGeolocationWriteRepository
    {
        if ($this->lotItemGeolocationWriteRepository === null) {
            $this->lotItemGeolocationWriteRepository = LotItemGeolocationWriteRepository::new();
        }
        return $this->lotItemGeolocationWriteRepository;
    }

    /**
     * @param LotItemGeolocationWriteRepository $lotItemGeolocationWriteRepository
     * @return static
     * @internal
     */
    public function setLotItemGeolocationWriteRepository(LotItemGeolocationWriteRepository $lotItemGeolocationWriteRepository): static
    {
        $this->lotItemGeolocationWriteRepository = $lotItemGeolocationWriteRepository;
        return $this;
    }
}
