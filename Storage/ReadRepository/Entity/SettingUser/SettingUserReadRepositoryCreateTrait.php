<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingUser;

trait SettingUserReadRepositoryCreateTrait
{
    protected ?SettingUserReadRepository $settingUserReadRepository = null;

    protected function createSettingUserReadRepository(): SettingUserReadRepository
    {
        return $this->settingUserReadRepository ?: SettingUserReadRepository::new();
    }

    /**
     * @param SettingUserReadRepository $settingUserReadRepository
     * @return static
     * @internal
     */
    public function setSettingUserReadRepository(SettingUserReadRepository $settingUserReadRepository): static
    {
        $this->settingUserReadRepository = $settingUserReadRepository;
        return $this;
    }
}
