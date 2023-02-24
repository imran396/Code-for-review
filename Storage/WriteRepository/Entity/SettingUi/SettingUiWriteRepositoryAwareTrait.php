<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingUi;

trait SettingUiWriteRepositoryAwareTrait
{
    protected ?SettingUiWriteRepository $settingUiWriteRepository = null;

    protected function getSettingUiWriteRepository(): SettingUiWriteRepository
    {
        if ($this->settingUiWriteRepository === null) {
            $this->settingUiWriteRepository = SettingUiWriteRepository::new();
        }
        return $this->settingUiWriteRepository;
    }

    /**
     * @param SettingUiWriteRepository $settingUiWriteRepository
     * @return static
     * @internal
     */
    public function setSettingUiWriteRepository(SettingUiWriteRepository $settingUiWriteRepository): static
    {
        $this->settingUiWriteRepository = $settingUiWriteRepository;
        return $this;
    }
}
