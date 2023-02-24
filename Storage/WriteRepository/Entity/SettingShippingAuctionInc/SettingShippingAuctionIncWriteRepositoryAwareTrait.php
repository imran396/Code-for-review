<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingShippingAuctionInc;

trait SettingShippingAuctionIncWriteRepositoryAwareTrait
{
    protected ?SettingShippingAuctionIncWriteRepository $settingShippingAuctionIncWriteRepository = null;

    protected function getSettingShippingAuctionIncWriteRepository(): SettingShippingAuctionIncWriteRepository
    {
        if ($this->settingShippingAuctionIncWriteRepository === null) {
            $this->settingShippingAuctionIncWriteRepository = SettingShippingAuctionIncWriteRepository::new();
        }
        return $this->settingShippingAuctionIncWriteRepository;
    }

    /**
     * @param SettingShippingAuctionIncWriteRepository $settingShippingAuctionIncWriteRepository
     * @return static
     * @internal
     */
    public function setSettingShippingAuctionIncWriteRepository(SettingShippingAuctionIncWriteRepository $settingShippingAuctionIncWriteRepository): static
    {
        $this->settingShippingAuctionIncWriteRepository = $settingShippingAuctionIncWriteRepository;
        return $this;
    }
}
