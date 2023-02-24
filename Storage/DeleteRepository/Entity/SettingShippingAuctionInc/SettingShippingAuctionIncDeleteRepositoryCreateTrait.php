<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingShippingAuctionInc;

trait SettingShippingAuctionIncDeleteRepositoryCreateTrait
{
    protected ?SettingShippingAuctionIncDeleteRepository $settingShippingAuctionIncDeleteRepository = null;

    protected function createSettingShippingAuctionIncDeleteRepository(): SettingShippingAuctionIncDeleteRepository
    {
        return $this->settingShippingAuctionIncDeleteRepository ?: SettingShippingAuctionIncDeleteRepository::new();
    }

    /**
     * @param SettingShippingAuctionIncDeleteRepository $settingShippingAuctionIncDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingShippingAuctionIncDeleteRepository(SettingShippingAuctionIncDeleteRepository $settingShippingAuctionIncDeleteRepository): static
    {
        $this->settingShippingAuctionIncDeleteRepository = $settingShippingAuctionIncDeleteRepository;
        return $this;
    }
}
