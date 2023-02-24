<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingBillingOpayo;

trait SettingBillingOpayoWriteRepositoryAwareTrait
{
    protected ?SettingBillingOpayoWriteRepository $settingBillingOpayoWriteRepository = null;

    protected function getSettingBillingOpayoWriteRepository(): SettingBillingOpayoWriteRepository
    {
        if ($this->settingBillingOpayoWriteRepository === null) {
            $this->settingBillingOpayoWriteRepository = SettingBillingOpayoWriteRepository::new();
        }
        return $this->settingBillingOpayoWriteRepository;
    }

    /**
     * @param SettingBillingOpayoWriteRepository $settingBillingOpayoWriteRepository
     * @return static
     * @internal
     */
    public function setSettingBillingOpayoWriteRepository(SettingBillingOpayoWriteRepository $settingBillingOpayoWriteRepository): static
    {
        $this->settingBillingOpayoWriteRepository = $settingBillingOpayoWriteRepository;
        return $this;
    }
}
