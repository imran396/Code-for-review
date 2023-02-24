<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingBillingPayTrace;

trait SettingBillingPayTraceReadRepositoryCreateTrait
{
    protected ?SettingBillingPayTraceReadRepository $settingBillingPayTraceReadRepository = null;

    protected function createSettingBillingPayTraceReadRepository(): SettingBillingPayTraceReadRepository
    {
        return $this->settingBillingPayTraceReadRepository ?: SettingBillingPayTraceReadRepository::new();
    }

    /**
     * @param SettingBillingPayTraceReadRepository $settingBillingPayTraceReadRepository
     * @return static
     * @internal
     */
    public function setSettingBillingPayTraceReadRepository(SettingBillingPayTraceReadRepository $settingBillingPayTraceReadRepository): static
    {
        $this->settingBillingPayTraceReadRepository = $settingBillingPayTraceReadRepository;
        return $this;
    }
}
