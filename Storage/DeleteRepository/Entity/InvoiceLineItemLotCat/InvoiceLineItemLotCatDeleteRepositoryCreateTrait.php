<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\InvoiceLineItemLotCat;

trait InvoiceLineItemLotCatDeleteRepositoryCreateTrait
{
    protected ?InvoiceLineItemLotCatDeleteRepository $invoiceLineItemLotCatDeleteRepository = null;

    protected function createInvoiceLineItemLotCatDeleteRepository(): InvoiceLineItemLotCatDeleteRepository
    {
        return $this->invoiceLineItemLotCatDeleteRepository ?: InvoiceLineItemLotCatDeleteRepository::new();
    }

    /**
     * @param InvoiceLineItemLotCatDeleteRepository $invoiceLineItemLotCatDeleteRepository
     * @return static
     * @internal
     */
    public function setInvoiceLineItemLotCatDeleteRepository(InvoiceLineItemLotCatDeleteRepository $invoiceLineItemLotCatDeleteRepository): static
    {
        $this->invoiceLineItemLotCatDeleteRepository = $invoiceLineItemLotCatDeleteRepository;
        return $this;
    }
}
