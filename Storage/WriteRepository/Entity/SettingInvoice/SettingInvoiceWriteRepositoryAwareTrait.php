<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingInvoice;

trait SettingInvoiceWriteRepositoryAwareTrait
{
    protected ?SettingInvoiceWriteRepository $settingInvoiceWriteRepository = null;

    protected function getSettingInvoiceWriteRepository(): SettingInvoiceWriteRepository
    {
        if ($this->settingInvoiceWriteRepository === null) {
            $this->settingInvoiceWriteRepository = SettingInvoiceWriteRepository::new();
        }
        return $this->settingInvoiceWriteRepository;
    }

    /**
     * @param SettingInvoiceWriteRepository $settingInvoiceWriteRepository
     * @return static
     * @internal
     */
    public function setSettingInvoiceWriteRepository(SettingInvoiceWriteRepository $settingInvoiceWriteRepository): static
    {
        $this->settingInvoiceWriteRepository = $settingInvoiceWriteRepository;
        return $this;
    }
}
