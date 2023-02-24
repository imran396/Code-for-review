<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingSettlementCheck;

trait SettingSettlementCheckWriteRepositoryAwareTrait
{
    protected ?SettingSettlementCheckWriteRepository $settingSettlementCheckWriteRepository = null;

    protected function getSettingSettlementCheckWriteRepository(): SettingSettlementCheckWriteRepository
    {
        if ($this->settingSettlementCheckWriteRepository === null) {
            $this->settingSettlementCheckWriteRepository = SettingSettlementCheckWriteRepository::new();
        }
        return $this->settingSettlementCheckWriteRepository;
    }

    /**
     * @param SettingSettlementCheckWriteRepository $settingSettlementCheckWriteRepository
     * @return static
     * @internal
     */
    public function setSettingSettlementCheckWriteRepository(SettingSettlementCheckWriteRepository $settingSettlementCheckWriteRepository): static
    {
        $this->settingSettlementCheckWriteRepository = $settingSettlementCheckWriteRepository;
        return $this;
    }
}
