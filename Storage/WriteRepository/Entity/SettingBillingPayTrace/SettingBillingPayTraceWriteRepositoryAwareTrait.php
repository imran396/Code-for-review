<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingBillingPayTrace;

trait SettingBillingPayTraceWriteRepositoryAwareTrait
{
    protected ?SettingBillingPayTraceWriteRepository $settingBillingPayTraceWriteRepository = null;

    protected function getSettingBillingPayTraceWriteRepository(): SettingBillingPayTraceWriteRepository
    {
        if ($this->settingBillingPayTraceWriteRepository === null) {
            $this->settingBillingPayTraceWriteRepository = SettingBillingPayTraceWriteRepository::new();
        }
        return $this->settingBillingPayTraceWriteRepository;
    }

    /**
     * @param SettingBillingPayTraceWriteRepository $settingBillingPayTraceWriteRepository
     * @return static
     * @internal
     */
    public function setSettingBillingPayTraceWriteRepository(SettingBillingPayTraceWriteRepository $settingBillingPayTraceWriteRepository): static
    {
        $this->settingBillingPayTraceWriteRepository = $settingBillingPayTraceWriteRepository;
        return $this;
    }
}
