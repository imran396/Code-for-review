<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingSystem;

trait SettingSystemReadRepositoryCreateTrait
{
    protected ?SettingSystemReadRepository $settingSystemReadRepository = null;

    protected function createSettingSystemReadRepository(): SettingSystemReadRepository
    {
        return $this->settingSystemReadRepository ?: SettingSystemReadRepository::new();
    }

    /**
     * @param SettingSystemReadRepository $settingSystemReadRepository
     * @return static
     * @internal
     */
    public function setSettingSystemReadRepository(SettingSystemReadRepository $settingSystemReadRepository): static
    {
        $this->settingSystemReadRepository = $settingSystemReadRepository;
        return $this;
    }
}
