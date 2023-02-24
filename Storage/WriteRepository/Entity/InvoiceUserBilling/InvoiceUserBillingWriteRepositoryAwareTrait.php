<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\InvoiceUserBilling;

trait InvoiceUserBillingWriteRepositoryAwareTrait
{
    protected ?InvoiceUserBillingWriteRepository $invoiceUserBillingWriteRepository = null;

    protected function getInvoiceUserBillingWriteRepository(): InvoiceUserBillingWriteRepository
    {
        if ($this->invoiceUserBillingWriteRepository === null) {
            $this->invoiceUserBillingWriteRepository = InvoiceUserBillingWriteRepository::new();
        }
        return $this->invoiceUserBillingWriteRepository;
    }

    /**
     * @param InvoiceUserBillingWriteRepository $invoiceUserBillingWriteRepository
     * @return static
     * @internal
     */
    public function setInvoiceUserBillingWriteRepository(InvoiceUserBillingWriteRepository $invoiceUserBillingWriteRepository): static
    {
        $this->invoiceUserBillingWriteRepository = $invoiceUserBillingWriteRepository;
        return $this;
    }
}
