<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingUi;

trait SettingUiReadRepositoryCreateTrait
{
    protected ?SettingUiReadRepository $settingUiReadRepository = null;

    protected function createSettingUiReadRepository(): SettingUiReadRepository
    {
        return $this->settingUiReadRepository ?: SettingUiReadRepository::new();
    }

    /**
     * @param SettingUiReadRepository $settingUiReadRepository
     * @return static
     * @internal
     */
    public function setSettingUiReadRepository(SettingUiReadRepository $settingUiReadRepository): static
    {
        $this->settingUiReadRepository = $settingUiReadRepository;
        return $this;
    }
}
