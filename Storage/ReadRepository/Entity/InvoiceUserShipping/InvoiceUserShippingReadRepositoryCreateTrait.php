<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\InvoiceUserShipping;

trait InvoiceUserShippingReadRepositoryCreateTrait
{
    protected ?InvoiceUserShippingReadRepository $invoiceUserShippingReadRepository = null;

    protected function createInvoiceUserShippingReadRepository(): InvoiceUserShippingReadRepository
    {
        return $this->invoiceUserShippingReadRepository ?: InvoiceUserShippingReadRepository::new();
    }

    /**
     * @param InvoiceUserShippingReadRepository $invoiceUserShippingReadRepository
     * @return static
     * @internal
     */
    public function setInvoiceUserShippingReadRepository(InvoiceUserShippingReadRepository $invoiceUserShippingReadRepository): static
    {
        $this->invoiceUserShippingReadRepository = $invoiceUserShippingReadRepository;
        return $this;
    }
}
