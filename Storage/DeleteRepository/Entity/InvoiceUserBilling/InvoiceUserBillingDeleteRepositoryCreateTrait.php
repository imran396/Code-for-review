<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\InvoiceUserBilling;

trait InvoiceUserBillingDeleteRepositoryCreateTrait
{
    protected ?InvoiceUserBillingDeleteRepository $invoiceUserBillingDeleteRepository = null;

    protected function createInvoiceUserBillingDeleteRepository(): InvoiceUserBillingDeleteRepository
    {
        return $this->invoiceUserBillingDeleteRepository ?: InvoiceUserBillingDeleteRepository::new();
    }

    /**
     * @param InvoiceUserBillingDeleteRepository $invoiceUserBillingDeleteRepository
     * @return static
     * @internal
     */
    public function setInvoiceUserBillingDeleteRepository(InvoiceUserBillingDeleteRepository $invoiceUserBillingDeleteRepository): static
    {
        $this->invoiceUserBillingDeleteRepository = $invoiceUserBillingDeleteRepository;
        return $this;
    }
}
