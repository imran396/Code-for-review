<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingPassword;

trait SettingPasswordReadRepositoryCreateTrait
{
    protected ?SettingPasswordReadRepository $settingPasswordReadRepository = null;

    protected function createSettingPasswordReadRepository(): SettingPasswordReadRepository
    {
        return $this->settingPasswordReadRepository ?: SettingPasswordReadRepository::new();
    }

    /**
     * @param SettingPasswordReadRepository $settingPasswordReadRepository
     * @return static
     * @internal
     */
    public function setSettingPasswordReadRepository(SettingPasswordReadRepository $settingPasswordReadRepository): static
    {
        $this->settingPasswordReadRepository = $settingPasswordReadRepository;
        return $this;
    }
}
