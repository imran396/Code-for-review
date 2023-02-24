<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\InvoicePaymentMethod;

trait InvoicePaymentMethodWriteRepositoryAwareTrait
{
    protected ?InvoicePaymentMethodWriteRepository $invoicePaymentMethodWriteRepository = null;

    protected function getInvoicePaymentMethodWriteRepository(): InvoicePaymentMethodWriteRepository
    {
        if ($this->invoicePaymentMethodWriteRepository === null) {
            $this->invoicePaymentMethodWriteRepository = InvoicePaymentMethodWriteRepository::new();
        }
        return $this->invoicePaymentMethodWriteRepository;
    }

    /**
     * @param InvoicePaymentMethodWriteRepository $invoicePaymentMethodWriteRepository
     * @return static
     * @internal
     */
    public function setInvoicePaymentMethodWriteRepository(InvoicePaymentMethodWriteRepository $invoicePaymentMethodWriteRepository): static
    {
        $this->invoicePaymentMethodWriteRepository = $invoicePaymentMethodWriteRepository;
        return $this;
    }
}
