<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Invoice;

trait InvoiceDeleteRepositoryCreateTrait
{
    protected ?InvoiceDeleteRepository $invoiceDeleteRepository = null;

    protected function createInvoiceDeleteRepository(): InvoiceDeleteRepository
    {
        return $this->invoiceDeleteRepository ?: InvoiceDeleteRepository::new();
    }

    /**
     * @param InvoiceDeleteRepository $invoiceDeleteRepository
     * @return static
     * @internal
     */
    public function setInvoiceDeleteRepository(InvoiceDeleteRepository $invoiceDeleteRepository): static
    {
        $this->invoiceDeleteRepository = $invoiceDeleteRepository;
        return $this;
    }
}
