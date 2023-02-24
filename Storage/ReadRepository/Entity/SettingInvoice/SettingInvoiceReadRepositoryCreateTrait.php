<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingInvoice;

trait SettingInvoiceReadRepositoryCreateTrait
{
    protected ?SettingInvoiceReadRepository $settingInvoiceReadRepository = null;

    protected function createSettingInvoiceReadRepository(): SettingInvoiceReadRepository
    {
        return $this->settingInvoiceReadRepository ?: SettingInvoiceReadRepository::new();
    }

    /**
     * @param SettingInvoiceReadRepository $settingInvoiceReadRepository
     * @return static
     * @internal
     */
    public function setSettingInvoiceReadRepository(SettingInvoiceReadRepository $settingInvoiceReadRepository): static
    {
        $this->settingInvoiceReadRepository = $settingInvoiceReadRepository;
        return $this;
    }
}
