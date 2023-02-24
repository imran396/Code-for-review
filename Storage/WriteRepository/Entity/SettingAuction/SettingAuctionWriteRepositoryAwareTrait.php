<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingAuction;

trait SettingAuctionWriteRepositoryAwareTrait
{
    protected ?SettingAuctionWriteRepository $settingAuctionWriteRepository = null;

    protected function getSettingAuctionWriteRepository(): SettingAuctionWriteRepository
    {
        if ($this->settingAuctionWriteRepository === null) {
            $this->settingAuctionWriteRepository = SettingAuctionWriteRepository::new();
        }
        return $this->settingAuctionWriteRepository;
    }

    /**
     * @param SettingAuctionWriteRepository $settingAuctionWriteRepository
     * @return static
     * @internal
     */
    public function setSettingAuctionWriteRepository(SettingAuctionWriteRepository $settingAuctionWriteRepository): static
    {
        $this->settingAuctionWriteRepository = $settingAuctionWriteRepository;
        return $this;
    }
}
