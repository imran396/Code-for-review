<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingBillingAuthorizeNet;

trait SettingBillingAuthorizeNetWriteRepositoryAwareTrait
{
    protected ?SettingBillingAuthorizeNetWriteRepository $settingBillingAuthorizeNetWriteRepository = null;

    protected function getSettingBillingAuthorizeNetWriteRepository(): SettingBillingAuthorizeNetWriteRepository
    {
        if ($this->settingBillingAuthorizeNetWriteRepository === null) {
            $this->settingBillingAuthorizeNetWriteRepository = SettingBillingAuthorizeNetWriteRepository::new();
        }
        return $this->settingBillingAuthorizeNetWriteRepository;
    }

    /**
     * @param SettingBillingAuthorizeNetWriteRepository $settingBillingAuthorizeNetWriteRepository
     * @return static
     * @internal
     */
    public function setSettingBillingAuthorizeNetWriteRepository(SettingBillingAuthorizeNetWriteRepository $settingBillingAuthorizeNetWriteRepository): static
    {
        $this->settingBillingAuthorizeNetWriteRepository = $settingBillingAuthorizeNetWriteRepository;
        return $this;
    }
}
