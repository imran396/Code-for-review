<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingBillingSmartPay;

trait SettingBillingSmartPayDeleteRepositoryCreateTrait
{
    protected ?SettingBillingSmartPayDeleteRepository $settingBillingSmartPayDeleteRepository = null;

    protected function createSettingBillingSmartPayDeleteRepository(): SettingBillingSmartPayDeleteRepository
    {
        return $this->settingBillingSmartPayDeleteRepository ?: SettingBillingSmartPayDeleteRepository::new();
    }

    /**
     * @param SettingBillingSmartPayDeleteRepository $settingBillingSmartPayDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingBillingSmartPayDeleteRepository(SettingBillingSmartPayDeleteRepository $settingBillingSmartPayDeleteRepository): static
    {
        $this->settingBillingSmartPayDeleteRepository = $settingBillingSmartPayDeleteRepository;
        return $this;
    }
}
