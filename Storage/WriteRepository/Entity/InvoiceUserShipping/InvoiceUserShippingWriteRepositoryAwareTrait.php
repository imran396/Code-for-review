<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\InvoiceUserShipping;

trait InvoiceUserShippingWriteRepositoryAwareTrait
{
    protected ?InvoiceUserShippingWriteRepository $invoiceUserShippingWriteRepository = null;

    protected function getInvoiceUserShippingWriteRepository(): InvoiceUserShippingWriteRepository
    {
        if ($this->invoiceUserShippingWriteRepository === null) {
            $this->invoiceUserShippingWriteRepository = InvoiceUserShippingWriteRepository::new();
        }
        return $this->invoiceUserShippingWriteRepository;
    }

    /**
     * @param InvoiceUserShippingWriteRepository $invoiceUserShippingWriteRepository
     * @return static
     * @internal
     */
    public function setInvoiceUserShippingWriteRepository(InvoiceUserShippingWriteRepository $invoiceUserShippingWriteRepository): static
    {
        $this->invoiceUserShippingWriteRepository = $invoiceUserShippingWriteRepository;
        return $this;
    }
}
