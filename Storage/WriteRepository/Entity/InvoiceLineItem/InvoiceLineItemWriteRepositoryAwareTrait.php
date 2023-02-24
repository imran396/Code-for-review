<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\InvoiceLineItem;

trait InvoiceLineItemWriteRepositoryAwareTrait
{
    protected ?InvoiceLineItemWriteRepository $invoiceLineItemWriteRepository = null;

    protected function getInvoiceLineItemWriteRepository(): InvoiceLineItemWriteRepository
    {
        if ($this->invoiceLineItemWriteRepository === null) {
            $this->invoiceLineItemWriteRepository = InvoiceLineItemWriteRepository::new();
        }
        return $this->invoiceLineItemWriteRepository;
    }

    /**
     * @param InvoiceLineItemWriteRepository $invoiceLineItemWriteRepository
     * @return static
     * @internal
     */
    public function setInvoiceLineItemWriteRepository(InvoiceLineItemWriteRepository $invoiceLineItemWriteRepository): static
    {
        $this->invoiceLineItemWriteRepository = $invoiceLineItemWriteRepository;
        return $this;
    }
}
