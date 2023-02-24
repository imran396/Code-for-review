<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingBillingAuthorizeNet;

trait SettingBillingAuthorizeNetDeleteRepositoryCreateTrait
{
    protected ?SettingBillingAuthorizeNetDeleteRepository $settingBillingAuthorizeNetDeleteRepository = null;

    protected function createSettingBillingAuthorizeNetDeleteRepository(): SettingBillingAuthorizeNetDeleteRepository
    {
        return $this->settingBillingAuthorizeNetDeleteRepository ?: SettingBillingAuthorizeNetDeleteRepository::new();
    }

    /**
     * @param SettingBillingAuthorizeNetDeleteRepository $settingBillingAuthorizeNetDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingBillingAuthorizeNetDeleteRepository(SettingBillingAuthorizeNetDeleteRepository $settingBillingAuthorizeNetDeleteRepository): static
    {
        $this->settingBillingAuthorizeNetDeleteRepository = $settingBillingAuthorizeNetDeleteRepository;
        return $this;
    }
}
