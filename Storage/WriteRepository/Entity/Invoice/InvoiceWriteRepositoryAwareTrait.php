<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Invoice;

trait InvoiceWriteRepositoryAwareTrait
{
    protected ?InvoiceWriteRepository $invoiceWriteRepository = null;

    protected function getInvoiceWriteRepository(): InvoiceWriteRepository
    {
        if ($this->invoiceWriteRepository === null) {
            $this->invoiceWriteRepository = InvoiceWriteRepository::new();
        }
        return $this->invoiceWriteRepository;
    }

    /**
     * @param InvoiceWriteRepository $invoiceWriteRepository
     * @return static
     * @internal
     */
    public function setInvoiceWriteRepository(InvoiceWriteRepository $invoiceWriteRepository): static
    {
        $this->invoiceWriteRepository = $invoiceWriteRepository;
        return $this;
    }
}
