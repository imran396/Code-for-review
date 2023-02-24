<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingSmtp;

trait SettingSmtpReadRepositoryCreateTrait
{
    protected ?SettingSmtpReadRepository $settingSmtpReadRepository = null;

    protected function createSettingSmtpReadRepository(): SettingSmtpReadRepository
    {
        return $this->settingSmtpReadRepository ?: SettingSmtpReadRepository::new();
    }

    /**
     * @param SettingSmtpReadRepository $settingSmtpReadRepository
     * @return static
     * @internal
     */
    public function setSettingSmtpReadRepository(SettingSmtpReadRepository $settingSmtpReadRepository): static
    {
        $this->settingSmtpReadRepository = $settingSmtpReadRepository;
        return $this;
    }
}
