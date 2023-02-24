<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\InvoiceLineItem;

trait InvoiceLineItemReadRepositoryCreateTrait
{
    protected ?InvoiceLineItemReadRepository $invoiceLineItemReadRepository = null;

    protected function createInvoiceLineItemReadRepository(): InvoiceLineItemReadRepository
    {
        return $this->invoiceLineItemReadRepository ?: InvoiceLineItemReadRepository::new();
    }

    /**
     * @param InvoiceLineItemReadRepository $invoiceLineItemReadRepository
     * @return static
     * @internal
     */
    public function setInvoiceLineItemReadRepository(InvoiceLineItemReadRepository $invoiceLineItemReadRepository): static
    {
        $this->invoiceLineItemReadRepository = $invoiceLineItemReadRepository;
        return $this;
    }
}
