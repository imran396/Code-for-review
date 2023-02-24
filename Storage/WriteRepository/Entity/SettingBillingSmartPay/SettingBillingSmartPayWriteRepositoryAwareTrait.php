<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingBillingSmartPay;

trait SettingBillingSmartPayWriteRepositoryAwareTrait
{
    protected ?SettingBillingSmartPayWriteRepository $settingBillingSmartPayWriteRepository = null;

    protected function getSettingBillingSmartPayWriteRepository(): SettingBillingSmartPayWriteRepository
    {
        if ($this->settingBillingSmartPayWriteRepository === null) {
            $this->settingBillingSmartPayWriteRepository = SettingBillingSmartPayWriteRepository::new();
        }
        return $this->settingBillingSmartPayWriteRepository;
    }

    /**
     * @param SettingBillingSmartPayWriteRepository $settingBillingSmartPayWriteRepository
     * @return static
     * @internal
     */
    public function setSettingBillingSmartPayWriteRepository(SettingBillingSmartPayWriteRepository $settingBillingSmartPayWriteRepository): static
    {
        $this->settingBillingSmartPayWriteRepository = $settingBillingSmartPayWriteRepository;
        return $this;
    }
}
