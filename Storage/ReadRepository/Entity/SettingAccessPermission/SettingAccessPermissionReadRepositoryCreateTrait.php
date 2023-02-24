<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingAccessPermission;

trait SettingAccessPermissionReadRepositoryCreateTrait
{
    protected ?SettingAccessPermissionReadRepository $settingAccessPermissionReadRepository = null;

    protected function createSettingAccessPermissionReadRepository(): SettingAccessPermissionReadRepository
    {
        return $this->settingAccessPermissionReadRepository ?: SettingAccessPermissionReadRepository::new();
    }

    /**
     * @param SettingAccessPermissionReadRepository $settingAccessPermissionReadRepository
     * @return static
     * @internal
     */
    public function setSettingAccessPermissionReadRepository(SettingAccessPermissionReadRepository $settingAccessPermissionReadRepository): static
    {
        $this->settingAccessPermissionReadRepository = $settingAccessPermissionReadRepository;
        return $this;
    }
}
