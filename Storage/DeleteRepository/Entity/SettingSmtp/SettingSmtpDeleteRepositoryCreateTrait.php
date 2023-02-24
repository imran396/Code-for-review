<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingSmtp;

trait SettingSmtpDeleteRepositoryCreateTrait
{
    protected ?SettingSmtpDeleteRepository $settingSmtpDeleteRepository = null;

    protected function createSettingSmtpDeleteRepository(): SettingSmtpDeleteRepository
    {
        return $this->settingSmtpDeleteRepository ?: SettingSmtpDeleteRepository::new();
    }

    /**
     * @param SettingSmtpDeleteRepository $settingSmtpDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingSmtpDeleteRepository(SettingSmtpDeleteRepository $settingSmtpDeleteRepository): static
    {
        $this->settingSmtpDeleteRepository = $settingSmtpDeleteRepository;
        return $this;
    }
}
