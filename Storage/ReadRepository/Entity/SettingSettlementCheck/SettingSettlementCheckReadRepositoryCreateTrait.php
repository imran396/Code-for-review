<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingSettlementCheck;

trait SettingSettlementCheckReadRepositoryCreateTrait
{
    protected ?SettingSettlementCheckReadRepository $settingSettlementCheckReadRepository = null;

    protected function createSettingSettlementCheckReadRepository(): SettingSettlementCheckReadRepository
    {
        return $this->settingSettlementCheckReadRepository ?: SettingSettlementCheckReadRepository::new();
    }

    /**
     * @param SettingSettlementCheckReadRepository $settingSettlementCheckReadRepository
     * @return static
     * @internal
     */
    public function setSettingSettlementCheckReadRepository(SettingSettlementCheckReadRepository $settingSettlementCheckReadRepository): static
    {
        $this->settingSettlementCheckReadRepository = $settingSettlementCheckReadRepository;
        return $this;
    }
}
