<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingBillingSmartPay;

trait SettingBillingSmartPayReadRepositoryCreateTrait
{
    protected ?SettingBillingSmartPayReadRepository $settingBillingSmartPayReadRepository = null;

    protected function createSettingBillingSmartPayReadRepository(): SettingBillingSmartPayReadRepository
    {
        return $this->settingBillingSmartPayReadRepository ?: SettingBillingSmartPayReadRepository::new();
    }

    /**
     * @param SettingBillingSmartPayReadRepository $settingBillingSmartPayReadRepository
     * @return static
     * @internal
     */
    public function setSettingBillingSmartPayReadRepository(SettingBillingSmartPayReadRepository $settingBillingSmartPayReadRepository): static
    {
        $this->settingBillingSmartPayReadRepository = $settingBillingSmartPayReadRepository;
        return $this;
    }
}
