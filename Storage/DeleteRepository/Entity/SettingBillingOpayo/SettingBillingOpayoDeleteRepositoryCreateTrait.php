<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingBillingOpayo;

trait SettingBillingOpayoDeleteRepositoryCreateTrait
{
    protected ?SettingBillingOpayoDeleteRepository $settingBillingOpayoDeleteRepository = null;

    protected function createSettingBillingOpayoDeleteRepository(): SettingBillingOpayoDeleteRepository
    {
        return $this->settingBillingOpayoDeleteRepository ?: SettingBillingOpayoDeleteRepository::new();
    }

    /**
     * @param SettingBillingOpayoDeleteRepository $settingBillingOpayoDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingBillingOpayoDeleteRepository(SettingBillingOpayoDeleteRepository $settingBillingOpayoDeleteRepository): static
    {
        $this->settingBillingOpayoDeleteRepository = $settingBillingOpayoDeleteRepository;
        return $this;
    }
}
