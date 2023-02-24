<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\InvoiceLineItemLotCat;

trait InvoiceLineItemLotCatWriteRepositoryAwareTrait
{
    protected ?InvoiceLineItemLotCatWriteRepository $invoiceLineItemLotCatWriteRepository = null;

    protected function getInvoiceLineItemLotCatWriteRepository(): InvoiceLineItemLotCatWriteRepository
    {
        if ($this->invoiceLineItemLotCatWriteRepository === null) {
            $this->invoiceLineItemLotCatWriteRepository = InvoiceLineItemLotCatWriteRepository::new();
        }
        return $this->invoiceLineItemLotCatWriteRepository;
    }

    /**
     * @param InvoiceLineItemLotCatWriteRepository $invoiceLineItemLotCatWriteRepository
     * @return static
     * @internal
     */
    public function setInvoiceLineItemLotCatWriteRepository(InvoiceLineItemLotCatWriteRepository $invoiceLineItemLotCatWriteRepository): static
    {
        $this->invoiceLineItemLotCatWriteRepository = $invoiceLineItemLotCatWriteRepository;
        return $this;
    }
}
