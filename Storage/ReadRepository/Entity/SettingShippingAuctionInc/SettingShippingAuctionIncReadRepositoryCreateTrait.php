<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingShippingAuctionInc;

trait SettingShippingAuctionIncReadRepositoryCreateTrait
{
    protected ?SettingShippingAuctionIncReadRepository $settingShippingAuctionIncReadRepository = null;

    protected function createSettingShippingAuctionIncReadRepository(): SettingShippingAuctionIncReadRepository
    {
        return $this->settingShippingAuctionIncReadRepository ?: SettingShippingAuctionIncReadRepository::new();
    }

    /**
     * @param SettingShippingAuctionIncReadRepository $settingShippingAuctionIncReadRepository
     * @return static
     * @internal
     */
    public function setSettingShippingAuctionIncReadRepository(SettingShippingAuctionIncReadRepository $settingShippingAuctionIncReadRepository): static
    {
        $this->settingShippingAuctionIncReadRepository = $settingShippingAuctionIncReadRepository;
        return $this;
    }
}
