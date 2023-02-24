<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingSystem;

trait SettingSystemDeleteRepositoryCreateTrait
{
    protected ?SettingSystemDeleteRepository $settingSystemDeleteRepository = null;

    protected function createSettingSystemDeleteRepository(): SettingSystemDeleteRepository
    {
        return $this->settingSystemDeleteRepository ?: SettingSystemDeleteRepository::new();
    }

    /**
     * @param SettingSystemDeleteRepository $settingSystemDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingSystemDeleteRepository(SettingSystemDeleteRepository $settingSystemDeleteRepository): static
    {
        $this->settingSystemDeleteRepository = $settingSystemDeleteRepository;
        return $this;
    }
}
