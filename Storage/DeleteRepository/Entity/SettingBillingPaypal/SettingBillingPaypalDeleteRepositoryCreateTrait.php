<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingBillingPaypal;

trait SettingBillingPaypalDeleteRepositoryCreateTrait
{
    protected ?SettingBillingPaypalDeleteRepository $settingBillingPaypalDeleteRepository = null;

    protected function createSettingBillingPaypalDeleteRepository(): SettingBillingPaypalDeleteRepository
    {
        return $this->settingBillingPaypalDeleteRepository ?: SettingBillingPaypalDeleteRepository::new();
    }

    /**
     * @param SettingBillingPaypalDeleteRepository $settingBillingPaypalDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingBillingPaypalDeleteRepository(SettingBillingPaypalDeleteRepository $settingBillingPaypalDeleteRepository): static
    {
        $this->settingBillingPaypalDeleteRepository = $settingBillingPaypalDeleteRepository;
        return $this;
    }
}
