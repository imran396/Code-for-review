<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingBillingEway;

trait SettingBillingEwayWriteRepositoryAwareTrait
{
    protected ?SettingBillingEwayWriteRepository $settingBillingEwayWriteRepository = null;

    protected function getSettingBillingEwayWriteRepository(): SettingBillingEwayWriteRepository
    {
        if ($this->settingBillingEwayWriteRepository === null) {
            $this->settingBillingEwayWriteRepository = SettingBillingEwayWriteRepository::new();
        }
        return $this->settingBillingEwayWriteRepository;
    }

    /**
     * @param SettingBillingEwayWriteRepository $settingBillingEwayWriteRepository
     * @return static
     * @internal
     */
    public function setSettingBillingEwayWriteRepository(SettingBillingEwayWriteRepository $settingBillingEwayWriteRepository): static
    {
        $this->settingBillingEwayWriteRepository = $settingBillingEwayWriteRepository;
        return $this;
    }
}
