<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingBillingNmi;

trait SettingBillingNmiDeleteRepositoryCreateTrait
{
    protected ?SettingBillingNmiDeleteRepository $settingBillingNmiDeleteRepository = null;

    protected function createSettingBillingNmiDeleteRepository(): SettingBillingNmiDeleteRepository
    {
        return $this->settingBillingNmiDeleteRepository ?: SettingBillingNmiDeleteRepository::new();
    }

    /**
     * @param SettingBillingNmiDeleteRepository $settingBillingNmiDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingBillingNmiDeleteRepository(SettingBillingNmiDeleteRepository $settingBillingNmiDeleteRepository): static
    {
        $this->settingBillingNmiDeleteRepository = $settingBillingNmiDeleteRepository;
        return $this;
    }
}
