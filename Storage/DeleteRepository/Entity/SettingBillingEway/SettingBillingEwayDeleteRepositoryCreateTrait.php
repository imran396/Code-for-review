<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingBillingEway;

trait SettingBillingEwayDeleteRepositoryCreateTrait
{
    protected ?SettingBillingEwayDeleteRepository $settingBillingEwayDeleteRepository = null;

    protected function createSettingBillingEwayDeleteRepository(): SettingBillingEwayDeleteRepository
    {
        return $this->settingBillingEwayDeleteRepository ?: SettingBillingEwayDeleteRepository::new();
    }

    /**
     * @param SettingBillingEwayDeleteRepository $settingBillingEwayDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingBillingEwayDeleteRepository(SettingBillingEwayDeleteRepository $settingBillingEwayDeleteRepository): static
    {
        $this->settingBillingEwayDeleteRepository = $settingBillingEwayDeleteRepository;
        return $this;
    }
}
