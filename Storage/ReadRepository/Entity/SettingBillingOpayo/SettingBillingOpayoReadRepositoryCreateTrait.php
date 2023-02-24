<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingBillingOpayo;

trait SettingBillingOpayoReadRepositoryCreateTrait
{
    protected ?SettingBillingOpayoReadRepository $settingBillingOpayoReadRepository = null;

    protected function createSettingBillingOpayoReadRepository(): SettingBillingOpayoReadRepository
    {
        return $this->settingBillingOpayoReadRepository ?: SettingBillingOpayoReadRepository::new();
    }

    /**
     * @param SettingBillingOpayoReadRepository $settingBillingOpayoReadRepository
     * @return static
     * @internal
     */
    public function setSettingBillingOpayoReadRepository(SettingBillingOpayoReadRepository $settingBillingOpayoReadRepository): static
    {
        $this->settingBillingOpayoReadRepository = $settingBillingOpayoReadRepository;
        return $this;
    }
}
