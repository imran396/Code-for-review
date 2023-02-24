<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\InvoiceItem;

trait InvoiceItemWriteRepositoryAwareTrait
{
    protected ?InvoiceItemWriteRepository $invoiceItemWriteRepository = null;

    protected function getInvoiceItemWriteRepository(): InvoiceItemWriteRepository
    {
        if ($this->invoiceItemWriteRepository === null) {
            $this->invoiceItemWriteRepository = InvoiceItemWriteRepository::new();
        }
        return $this->invoiceItemWriteRepository;
    }

    /**
     * @param InvoiceItemWriteRepository $invoiceItemWriteRepository
     * @return static
     * @internal
     */
    public function setInvoiceItemWriteRepository(InvoiceItemWriteRepository $invoiceItemWriteRepository): static
    {
        $this->invoiceItemWriteRepository = $invoiceItemWriteRepository;
        return $this;
    }
}
