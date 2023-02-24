<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\InvoiceItem;

trait InvoiceItemDeleteRepositoryCreateTrait
{
    protected ?InvoiceItemDeleteRepository $invoiceItemDeleteRepository = null;

    protected function createInvoiceItemDeleteRepository(): InvoiceItemDeleteRepository
    {
        return $this->invoiceItemDeleteRepository ?: InvoiceItemDeleteRepository::new();
    }

    /**
     * @param InvoiceItemDeleteRepository $invoiceItemDeleteRepository
     * @return static
     * @internal
     */
    public function setInvoiceItemDeleteRepository(InvoiceItemDeleteRepository $invoiceItemDeleteRepository): static
    {
        $this->invoiceItemDeleteRepository = $invoiceItemDeleteRepository;
        return $this;
    }
}
