<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingBillingNmi;

trait SettingBillingNmiReadRepositoryCreateTrait
{
    protected ?SettingBillingNmiReadRepository $settingBillingNmiReadRepository = null;

    protected function createSettingBillingNmiReadRepository(): SettingBillingNmiReadRepository
    {
        return $this->settingBillingNmiReadRepository ?: SettingBillingNmiReadRepository::new();
    }

    /**
     * @param SettingBillingNmiReadRepository $settingBillingNmiReadRepository
     * @return static
     * @internal
     */
    public function setSettingBillingNmiReadRepository(SettingBillingNmiReadRepository $settingBillingNmiReadRepository): static
    {
        $this->settingBillingNmiReadRepository = $settingBillingNmiReadRepository;
        return $this;
    }
}
