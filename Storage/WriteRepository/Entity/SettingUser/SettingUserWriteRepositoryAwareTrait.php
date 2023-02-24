<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingUser;

trait SettingUserWriteRepositoryAwareTrait
{
    protected ?SettingUserWriteRepository $settingUserWriteRepository = null;

    protected function getSettingUserWriteRepository(): SettingUserWriteRepository
    {
        if ($this->settingUserWriteRepository === null) {
            $this->settingUserWriteRepository = SettingUserWriteRepository::new();
        }
        return $this->settingUserWriteRepository;
    }

    /**
     * @param SettingUserWriteRepository $settingUserWriteRepository
     * @return static
     * @internal
     */
    public function setSettingUserWriteRepository(SettingUserWriteRepository $settingUserWriteRepository): static
    {
        $this->settingUserWriteRepository = $settingUserWriteRepository;
        return $this;
    }
}
