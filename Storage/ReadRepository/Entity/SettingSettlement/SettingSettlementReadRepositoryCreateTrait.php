<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingSettlement;

trait SettingSettlementReadRepositoryCreateTrait
{
    protected ?SettingSettlementReadRepository $settingSettlementReadRepository = null;

    protected function createSettingSettlementReadRepository(): SettingSettlementReadRepository
    {
        return $this->settingSettlementReadRepository ?: SettingSettlementReadRepository::new();
    }

    /**
     * @param SettingSettlementReadRepository $settingSettlementReadRepository
     * @return static
     * @internal
     */
    public function setSettingSettlementReadRepository(SettingSettlementReadRepository $settingSettlementReadRepository): static
    {
        $this->settingSettlementReadRepository = $settingSettlementReadRepository;
        return $this;
    }
}
