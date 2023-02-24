<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingUi;

trait SettingUiDeleteRepositoryCreateTrait
{
    protected ?SettingUiDeleteRepository $settingUiDeleteRepository = null;

    protected function createSettingUiDeleteRepository(): SettingUiDeleteRepository
    {
        return $this->settingUiDeleteRepository ?: SettingUiDeleteRepository::new();
    }

    /**
     * @param SettingUiDeleteRepository $settingUiDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingUiDeleteRepository(SettingUiDeleteRepository $settingUiDeleteRepository): static
    {
        $this->settingUiDeleteRepository = $settingUiDeleteRepository;
        return $this;
    }
}
