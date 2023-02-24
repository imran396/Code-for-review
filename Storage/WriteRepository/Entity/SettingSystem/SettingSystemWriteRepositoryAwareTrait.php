<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingSystem;

trait SettingSystemWriteRepositoryAwareTrait
{
    protected ?SettingSystemWriteRepository $settingSystemWriteRepository = null;

    protected function getSettingSystemWriteRepository(): SettingSystemWriteRepository
    {
        if ($this->settingSystemWriteRepository === null) {
            $this->settingSystemWriteRepository = SettingSystemWriteRepository::new();
        }
        return $this->settingSystemWriteRepository;
    }

    /**
     * @param SettingSystemWriteRepository $settingSystemWriteRepository
     * @return static
     * @internal
     */
    public function setSettingSystemWriteRepository(SettingSystemWriteRepository $settingSystemWriteRepository): static
    {
        $this->settingSystemWriteRepository = $settingSystemWriteRepository;
        return $this;
    }
}
