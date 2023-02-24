<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingPassword;

trait SettingPasswordWriteRepositoryAwareTrait
{
    protected ?SettingPasswordWriteRepository $settingPasswordWriteRepository = null;

    protected function getSettingPasswordWriteRepository(): SettingPasswordWriteRepository
    {
        if ($this->settingPasswordWriteRepository === null) {
            $this->settingPasswordWriteRepository = SettingPasswordWriteRepository::new();
        }
        return $this->settingPasswordWriteRepository;
    }

    /**
     * @param SettingPasswordWriteRepository $settingPasswordWriteRepository
     * @return static
     * @internal
     */
    public function setSettingPasswordWriteRepository(SettingPasswordWriteRepository $settingPasswordWriteRepository): static
    {
        $this->settingPasswordWriteRepository = $settingPasswordWriteRepository;
        return $this;
    }
}
