<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingSettlement;

trait SettingSettlementDeleteRepositoryCreateTrait
{
    protected ?SettingSettlementDeleteRepository $settingSettlementDeleteRepository = null;

    protected function createSettingSettlementDeleteRepository(): SettingSettlementDeleteRepository
    {
        return $this->settingSettlementDeleteRepository ?: SettingSettlementDeleteRepository::new();
    }

    /**
     * @param SettingSettlementDeleteRepository $settingSettlementDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingSettlementDeleteRepository(SettingSettlementDeleteRepository $settingSettlementDeleteRepository): static
    {
        $this->settingSettlementDeleteRepository = $settingSettlementDeleteRepository;
        return $this;
    }
}
