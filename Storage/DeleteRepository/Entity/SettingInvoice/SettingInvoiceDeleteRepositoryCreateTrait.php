<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingInvoice;

trait SettingInvoiceDeleteRepositoryCreateTrait
{
    protected ?SettingInvoiceDeleteRepository $settingInvoiceDeleteRepository = null;

    protected function createSettingInvoiceDeleteRepository(): SettingInvoiceDeleteRepository
    {
        return $this->settingInvoiceDeleteRepository ?: SettingInvoiceDeleteRepository::new();
    }

    /**
     * @param SettingInvoiceDeleteRepository $settingInvoiceDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingInvoiceDeleteRepository(SettingInvoiceDeleteRepository $settingInvoiceDeleteRepository): static
    {
        $this->settingInvoiceDeleteRepository = $settingInvoiceDeleteRepository;
        return $this;
    }
}
