<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingSettlementCheck;

trait SettingSettlementCheckDeleteRepositoryCreateTrait
{
    protected ?SettingSettlementCheckDeleteRepository $settingSettlementCheckDeleteRepository = null;

    protected function createSettingSettlementCheckDeleteRepository(): SettingSettlementCheckDeleteRepository
    {
        return $this->settingSettlementCheckDeleteRepository ?: SettingSettlementCheckDeleteRepository::new();
    }

    /**
     * @param SettingSettlementCheckDeleteRepository $settingSettlementCheckDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingSettlementCheckDeleteRepository(SettingSettlementCheckDeleteRepository $settingSettlementCheckDeleteRepository): static
    {
        $this->settingSettlementCheckDeleteRepository = $settingSettlementCheckDeleteRepository;
        return $this;
    }
}
