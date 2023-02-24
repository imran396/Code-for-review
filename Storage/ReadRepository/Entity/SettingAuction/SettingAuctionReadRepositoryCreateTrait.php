<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingAuction;

trait SettingAuctionReadRepositoryCreateTrait
{
    protected ?SettingAuctionReadRepository $settingAuctionReadRepository = null;

    protected function createSettingAuctionReadRepository(): SettingAuctionReadRepository
    {
        return $this->settingAuctionReadRepository ?: SettingAuctionReadRepository::new();
    }

    /**
     * @param SettingAuctionReadRepository $settingAuctionReadRepository
     * @return static
     * @internal
     */
    public function setSettingAuctionReadRepository(SettingAuctionReadRepository $settingAuctionReadRepository): static
    {
        $this->settingAuctionReadRepository = $settingAuctionReadRepository;
        return $this;
    }
}
