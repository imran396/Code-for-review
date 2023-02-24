<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingBillingPaypal;

trait SettingBillingPaypalWriteRepositoryAwareTrait
{
    protected ?SettingBillingPaypalWriteRepository $settingBillingPaypalWriteRepository = null;

    protected function getSettingBillingPaypalWriteRepository(): SettingBillingPaypalWriteRepository
    {
        if ($this->settingBillingPaypalWriteRepository === null) {
            $this->settingBillingPaypalWriteRepository = SettingBillingPaypalWriteRepository::new();
        }
        return $this->settingBillingPaypalWriteRepository;
    }

    /**
     * @param SettingBillingPaypalWriteRepository $settingBillingPaypalWriteRepository
     * @return static
     * @internal
     */
    public function setSettingBillingPaypalWriteRepository(SettingBillingPaypalWriteRepository $settingBillingPaypalWriteRepository): static
    {
        $this->settingBillingPaypalWriteRepository = $settingBillingPaypalWriteRepository;
        return $this;
    }
}
