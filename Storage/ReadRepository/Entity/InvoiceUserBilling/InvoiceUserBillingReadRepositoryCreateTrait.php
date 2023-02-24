<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\InvoiceUserBilling;

trait InvoiceUserBillingReadRepositoryCreateTrait
{
    protected ?InvoiceUserBillingReadRepository $invoiceUserBillingReadRepository = null;

    protected function createInvoiceUserBillingReadRepository(): InvoiceUserBillingReadRepository
    {
        return $this->invoiceUserBillingReadRepository ?: InvoiceUserBillingReadRepository::new();
    }

    /**
     * @param InvoiceUserBillingReadRepository $invoiceUserBillingReadRepository
     * @return static
     * @internal
     */
    public function setInvoiceUserBillingReadRepository(InvoiceUserBillingReadRepository $invoiceUserBillingReadRepository): static
    {
        $this->invoiceUserBillingReadRepository = $invoiceUserBillingReadRepository;
        return $this;
    }
}
