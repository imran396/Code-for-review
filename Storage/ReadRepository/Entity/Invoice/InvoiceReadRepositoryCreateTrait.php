<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Invoice;

trait InvoiceReadRepositoryCreateTrait
{
    protected ?InvoiceReadRepository $invoiceReadRepository = null;

    protected function createInvoiceReadRepository(): InvoiceReadRepository
    {
        return $this->invoiceReadRepository ?: InvoiceReadRepository::new();
    }

    /**
     * @param InvoiceReadRepository $invoiceReadRepository
     * @return static
     * @internal
     */
    public function setInvoiceReadRepository(InvoiceReadRepository $invoiceReadRepository): static
    {
        $this->invoiceReadRepository = $invoiceReadRepository;
        return $this;
    }
}
