<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingBillingPaypal;

trait SettingBillingPaypalReadRepositoryCreateTrait
{
    protected ?SettingBillingPaypalReadRepository $settingBillingPaypalReadRepository = null;

    protected function createSettingBillingPaypalReadRepository(): SettingBillingPaypalReadRepository
    {
        return $this->settingBillingPaypalReadRepository ?: SettingBillingPaypalReadRepository::new();
    }

    /**
     * @param SettingBillingPaypalReadRepository $settingBillingPaypalReadRepository
     * @return static
     * @internal
     */
    public function setSettingBillingPaypalReadRepository(SettingBillingPaypalReadRepository $settingBillingPaypalReadRepository): static
    {
        $this->settingBillingPaypalReadRepository = $settingBillingPaypalReadRepository;
        return $this;
    }
}
