<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\InvoiceLineItem;

trait InvoiceLineItemDeleteRepositoryCreateTrait
{
    protected ?InvoiceLineItemDeleteRepository $invoiceLineItemDeleteRepository = null;

    protected function createInvoiceLineItemDeleteRepository(): InvoiceLineItemDeleteRepository
    {
        return $this->invoiceLineItemDeleteRepository ?: InvoiceLineItemDeleteRepository::new();
    }

    /**
     * @param InvoiceLineItemDeleteRepository $invoiceLineItemDeleteRepository
     * @return static
     * @internal
     */
    public function setInvoiceLineItemDeleteRepository(InvoiceLineItemDeleteRepository $invoiceLineItemDeleteRepository): static
    {
        $this->invoiceLineItemDeleteRepository = $invoiceLineItemDeleteRepository;
        return $this;
    }
}
