<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingSettlement;

trait SettingSettlementWriteRepositoryAwareTrait
{
    protected ?SettingSettlementWriteRepository $settingSettlementWriteRepository = null;

    protected function getSettingSettlementWriteRepository(): SettingSettlementWriteRepository
    {
        if ($this->settingSettlementWriteRepository === null) {
            $this->settingSettlementWriteRepository = SettingSettlementWriteRepository::new();
        }
        return $this->settingSettlementWriteRepository;
    }

    /**
     * @param SettingSettlementWriteRepository $settingSettlementWriteRepository
     * @return static
     * @internal
     */
    public function setSettingSettlementWriteRepository(SettingSettlementWriteRepository $settingSettlementWriteRepository): static
    {
        $this->settingSettlementWriteRepository = $settingSettlementWriteRepository;
        return $this;
    }
}
