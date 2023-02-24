<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingRtb;

trait SettingRtbReadRepositoryCreateTrait
{
    protected ?SettingRtbReadRepository $settingRtbReadRepository = null;

    protected function createSettingRtbReadRepository(): SettingRtbReadRepository
    {
        return $this->settingRtbReadRepository ?: SettingRtbReadRepository::new();
    }

    /**
     * @param SettingRtbReadRepository $settingRtbReadRepository
     * @return static
     * @internal
     */
    public function setSettingRtbReadRepository(SettingRtbReadRepository $settingRtbReadRepository): static
    {
        $this->settingRtbReadRepository = $settingRtbReadRepository;
        return $this;
    }
}
