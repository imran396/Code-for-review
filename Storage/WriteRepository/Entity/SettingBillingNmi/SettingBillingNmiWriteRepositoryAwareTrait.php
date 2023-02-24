<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingBillingNmi;

trait SettingBillingNmiWriteRepositoryAwareTrait
{
    protected ?SettingBillingNmiWriteRepository $settingBillingNmiWriteRepository = null;

    protected function getSettingBillingNmiWriteRepository(): SettingBillingNmiWriteRepository
    {
        if ($this->settingBillingNmiWriteRepository === null) {
            $this->settingBillingNmiWriteRepository = SettingBillingNmiWriteRepository::new();
        }
        return $this->settingBillingNmiWriteRepository;
    }

    /**
     * @param SettingBillingNmiWriteRepository $settingBillingNmiWriteRepository
     * @return static
     * @internal
     */
    public function setSettingBillingNmiWriteRepository(SettingBillingNmiWriteRepository $settingBillingNmiWriteRepository): static
    {
        $this->settingBillingNmiWriteRepository = $settingBillingNmiWriteRepository;
        return $this;
    }
}
