<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\InvoiceUserShipping;

trait InvoiceUserShippingDeleteRepositoryCreateTrait
{
    protected ?InvoiceUserShippingDeleteRepository $invoiceUserShippingDeleteRepository = null;

    protected function createInvoiceUserShippingDeleteRepository(): InvoiceUserShippingDeleteRepository
    {
        return $this->invoiceUserShippingDeleteRepository ?: InvoiceUserShippingDeleteRepository::new();
    }

    /**
     * @param InvoiceUserShippingDeleteRepository $invoiceUserShippingDeleteRepository
     * @return static
     * @internal
     */
    public function setInvoiceUserShippingDeleteRepository(InvoiceUserShippingDeleteRepository $invoiceUserShippingDeleteRepository): static
    {
        $this->invoiceUserShippingDeleteRepository = $invoiceUserShippingDeleteRepository;
        return $this;
    }
}
