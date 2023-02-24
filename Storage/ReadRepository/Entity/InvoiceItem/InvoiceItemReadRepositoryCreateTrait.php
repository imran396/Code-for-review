<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\InvoiceItem;

trait InvoiceItemReadRepositoryCreateTrait
{
    protected ?InvoiceItemReadRepository $invoiceItemReadRepository = null;

    protected function createInvoiceItemReadRepository(): InvoiceItemReadRepository
    {
        return $this->invoiceItemReadRepository ?: InvoiceItemReadRepository::new();
    }

    /**
     * @param InvoiceItemReadRepository $invoiceItemReadRepository
     * @return static
     * @internal
     */
    public function setInvoiceItemReadRepository(InvoiceItemReadRepository $invoiceItemReadRepository): static
    {
        $this->invoiceItemReadRepository = $invoiceItemReadRepository;
        return $this;
    }
}
