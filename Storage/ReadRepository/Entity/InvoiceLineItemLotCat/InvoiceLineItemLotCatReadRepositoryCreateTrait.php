<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\InvoiceLineItemLotCat;

trait InvoiceLineItemLotCatReadRepositoryCreateTrait
{
    protected ?InvoiceLineItemLotCatReadRepository $invoiceLineItemLotCatReadRepository = null;

    protected function createInvoiceLineItemLotCatReadRepository(): InvoiceLineItemLotCatReadRepository
    {
        return $this->invoiceLineItemLotCatReadRepository ?: InvoiceLineItemLotCatReadRepository::new();
    }

    /**
     * @param InvoiceLineItemLotCatReadRepository $invoiceLineItemLotCatReadRepository
     * @return static
     * @internal
     */
    public function setInvoiceLineItemLotCatReadRepository(InvoiceLineItemLotCatReadRepository $invoiceLineItemLotCatReadRepository): static
    {
        $this->invoiceLineItemLotCatReadRepository = $invoiceLineItemLotCatReadRepository;
        return $this;
    }
}
