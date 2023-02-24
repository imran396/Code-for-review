<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\InvoicePaymentMethod;

trait InvoicePaymentMethodReadRepositoryCreateTrait
{
    protected ?InvoicePaymentMethodReadRepository $invoicePaymentMethodReadRepository = null;

    protected function createInvoicePaymentMethodReadRepository(): InvoicePaymentMethodReadRepository
    {
        return $this->invoicePaymentMethodReadRepository ?: InvoicePaymentMethodReadRepository::new();
    }

    /**
     * @param InvoicePaymentMethodReadRepository $invoicePaymentMethodReadRepository
     * @return static
     * @internal
     */
    public function setInvoicePaymentMethodReadRepository(InvoicePaymentMethodReadRepository $invoicePaymentMethodReadRepository): static
    {
        $this->invoicePaymentMethodReadRepository = $invoicePaymentMethodReadRepository;
        return $this;
    }
}
