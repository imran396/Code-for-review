<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingAccessPermission;

trait SettingAccessPermissionWriteRepositoryAwareTrait
{
    protected ?SettingAccessPermissionWriteRepository $settingAccessPermissionWriteRepository = null;

    protected function getSettingAccessPermissionWriteRepository(): SettingAccessPermissionWriteRepository
    {
        if ($this->settingAccessPermissionWriteRepository === null) {
            $this->settingAccessPermissionWriteRepository = SettingAccessPermissionWriteRepository::new();
        }
        return $this->settingAccessPermissionWriteRepository;
    }

    /**
     * @param SettingAccessPermissionWriteRepository $settingAccessPermissionWriteRepository
     * @return static
     * @internal
     */
    public function setSettingAccessPermissionWriteRepository(SettingAccessPermissionWriteRepository $settingAccessPermissionWriteRepository): static
    {
        $this->settingAccessPermissionWriteRepository = $settingAccessPermissionWriteRepository;
        return $this;
    }
}
