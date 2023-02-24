<?php

/**
 * Pay attention, we clear manager properties on get, so it could be used in multiple bid processing
 */

namespace Sam\Bidding\AbsenteeBid\Place;

/**
 * Trait AbsenteeBidManagerAwareTrait
 * @package Sam\Bidding\AbsenteeBid\Place
 */
trait AbsenteeBidManagerCreateTrait
{
    protected ?AbsenteeBidManager $absenteeBidManager = null;

    /**
     * @return AbsenteeBidManager
     */
    protected function createAbsenteeBidManager(): AbsenteeBidManager
    {
        $absenteeBidManager = $this->absenteeBidManager ?: AbsenteeBidManager::new();
        return $absenteeBidManager;
    }

    /**
     * @param AbsenteeBidManager $absenteeBidManager
     * @return static
     * @internal
     */
    public function setAbsenteeBidManager(AbsenteeBidManager $absenteeBidManager): static
    {
        $this->absenteeBidManager = $absenteeBidManager;
        return $this;
    }
}
