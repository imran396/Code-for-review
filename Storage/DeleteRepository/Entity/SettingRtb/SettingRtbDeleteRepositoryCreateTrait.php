<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingRtb;

trait SettingRtbDeleteRepositoryCreateTrait
{
    protected ?SettingRtbDeleteRepository $settingRtbDeleteRepository = null;

    protected function createSettingRtbDeleteRepository(): SettingRtbDeleteRepository
    {
        return $this->settingRtbDeleteRepository ?: SettingRtbDeleteRepository::new();
    }

    /**
     * @param SettingRtbDeleteRepository $settingRtbDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingRtbDeleteRepository(SettingRtbDeleteRepository $settingRtbDeleteRepository): static
    {
        $this->settingRtbDeleteRepository = $settingRtbDeleteRepository;
        return $this;
    }
}
