<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingBillingPayTrace;

trait SettingBillingPayTraceDeleteRepositoryCreateTrait
{
    protected ?SettingBillingPayTraceDeleteRepository $settingBillingPayTraceDeleteRepository = null;

    protected function createSettingBillingPayTraceDeleteRepository(): SettingBillingPayTraceDeleteRepository
    {
        return $this->settingBillingPayTraceDeleteRepository ?: SettingBillingPayTraceDeleteRepository::new();
    }

    /**
     * @param SettingBillingPayTraceDeleteRepository $settingBillingPayTraceDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingBillingPayTraceDeleteRepository(SettingBillingPayTraceDeleteRepository $settingBillingPayTraceDeleteRepository): static
    {
        $this->settingBillingPayTraceDeleteRepository = $settingBillingPayTraceDeleteRepository;
        return $this;
    }
}
