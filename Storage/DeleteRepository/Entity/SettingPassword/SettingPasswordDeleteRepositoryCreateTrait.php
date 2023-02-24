<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingPassword;

trait SettingPasswordDeleteRepositoryCreateTrait
{
    protected ?SettingPasswordDeleteRepository $settingPasswordDeleteRepository = null;

    protected function createSettingPasswordDeleteRepository(): SettingPasswordDeleteRepository
    {
        return $this->settingPasswordDeleteRepository ?: SettingPasswordDeleteRepository::new();
    }

    /**
     * @param SettingPasswordDeleteRepository $settingPasswordDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingPasswordDeleteRepository(SettingPasswordDeleteRepository $settingPasswordDeleteRepository): static
    {
        $this->settingPasswordDeleteRepository = $settingPasswordDeleteRepository;
        return $this;
    }
}
