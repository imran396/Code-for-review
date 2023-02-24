<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingBillingAuthorizeNet;

trait SettingBillingAuthorizeNetReadRepositoryCreateTrait
{
    protected ?SettingBillingAuthorizeNetReadRepository $settingBillingAuthorizeNetReadRepository = null;

    protected function createSettingBillingAuthorizeNetReadRepository(): SettingBillingAuthorizeNetReadRepository
    {
        return $this->settingBillingAuthorizeNetReadRepository ?: SettingBillingAuthorizeNetReadRepository::new();
    }

    /**
     * @param SettingBillingAuthorizeNetReadRepository $settingBillingAuthorizeNetReadRepository
     * @return static
     * @internal
     */
    public function setSettingBillingAuthorizeNetReadRepository(SettingBillingAuthorizeNetReadRepository $settingBillingAuthorizeNetReadRepository): static
    {
        $this->settingBillingAuthorizeNetReadRepository = $settingBillingAuthorizeNetReadRepository;
        return $this;
    }
}
