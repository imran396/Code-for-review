<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingBillingEway;

trait SettingBillingEwayReadRepositoryCreateTrait
{
    protected ?SettingBillingEwayReadRepository $settingBillingEwayReadRepository = null;

    protected function createSettingBillingEwayReadRepository(): SettingBillingEwayReadRepository
    {
        return $this->settingBillingEwayReadRepository ?: SettingBillingEwayReadRepository::new();
    }

    /**
     * @param SettingBillingEwayReadRepository $settingBillingEwayReadRepository
     * @return static
     * @internal
     */
    public function setSettingBillingEwayReadRepository(SettingBillingEwayReadRepository $settingBillingEwayReadRepository): static
    {
        $this->settingBillingEwayReadRepository = $settingBillingEwayReadRepository;
        return $this;
    }
}
