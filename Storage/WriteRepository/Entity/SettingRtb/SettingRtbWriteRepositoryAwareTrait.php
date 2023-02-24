<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingRtb;

trait SettingRtbWriteRepositoryAwareTrait
{
    protected ?SettingRtbWriteRepository $settingRtbWriteRepository = null;

    protected function getSettingRtbWriteRepository(): SettingRtbWriteRepository
    {
        if ($this->settingRtbWriteRepository === null) {
            $this->settingRtbWriteRepository = SettingRtbWriteRepository::new();
        }
        return $this->settingRtbWriteRepository;
    }

    /**
     * @param SettingRtbWriteRepository $settingRtbWriteRepository
     * @return static
     * @internal
     */
    public function setSettingRtbWriteRepository(SettingRtbWriteRepository $settingRtbWriteRepository): static
    {
        $this->settingRtbWriteRepository = $settingRtbWriteRepository;
        return $this;
    }
}
