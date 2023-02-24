<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingSmtp;

trait SettingSmtpWriteRepositoryAwareTrait
{
    protected ?SettingSmtpWriteRepository $settingSmtpWriteRepository = null;

    protected function getSettingSmtpWriteRepository(): SettingSmtpWriteRepository
    {
        if ($this->settingSmtpWriteRepository === null) {
            $this->settingSmtpWriteRepository = SettingSmtpWriteRepository::new();
        }
        return $this->settingSmtpWriteRepository;
    }

    /**
     * @param SettingSmtpWriteRepository $settingSmtpWriteRepository
     * @return static
     * @internal
     */
    public function setSettingSmtpWriteRepository(SettingSmtpWriteRepository $settingSmtpWriteRepository): static
    {
        $this->settingSmtpWriteRepository = $settingSmtpWriteRepository;
        return $this;
    }
}
