<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingAccessPermission;

trait SettingAccessPermissionDeleteRepositoryCreateTrait
{
    protected ?SettingAccessPermissionDeleteRepository $settingAccessPermissionDeleteRepository = null;

    protected function createSettingAccessPermissionDeleteRepository(): SettingAccessPermissionDeleteRepository
    {
        return $this->settingAccessPermissionDeleteRepository ?: SettingAccessPermissionDeleteRepository::new();
    }

    /**
     * @param SettingAccessPermissionDeleteRepository $settingAccessPermissionDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingAccessPermissionDeleteRepository(SettingAccessPermissionDeleteRepository $settingAccessPermissionDeleteRepository): static
    {
        $this->settingAccessPermissionDeleteRepository = $settingAccessPermissionDeleteRepository;
        return $this;
    }
}
