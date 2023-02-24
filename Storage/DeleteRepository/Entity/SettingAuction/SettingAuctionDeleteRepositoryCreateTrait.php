<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingAuction;

trait SettingAuctionDeleteRepositoryCreateTrait
{
    protected ?SettingAuctionDeleteRepository $settingAuctionDeleteRepository = null;

    protected function createSettingAuctionDeleteRepository(): SettingAuctionDeleteRepository
    {
        return $this->settingAuctionDeleteRepository ?: SettingAuctionDeleteRepository::new();
    }

    /**
     * @param SettingAuctionDeleteRepository $settingAuctionDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingAuctionDeleteRepository(SettingAuctionDeleteRepository $settingAuctionDeleteRepository): static
    {
        $this->settingAuctionDeleteRepository = $settingAuctionDeleteRepository;
        return $this;
    }
}
