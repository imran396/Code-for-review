<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingUser;

trait SettingUserDeleteRepositoryCreateTrait
{
    protected ?SettingUserDeleteRepository $settingUserDeleteRepository = null;

    protected function createSettingUserDeleteRepository(): SettingUserDeleteRepository
    {
        return $this->settingUserDeleteRepository ?: SettingUserDeleteRepository::new();
    }

    /**
     * @param SettingUserDeleteRepository $settingUserDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingUserDeleteRepository(SettingUserDeleteRepository $settingUserDeleteRepository): static
    {
        $this->settingUserDeleteRepository = $settingUserDeleteRepository;
        return $this;
    }
}
