<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\InvoicePaymentMethod;

trait InvoicePaymentMethodDeleteRepositoryCreateTrait
{
    protected ?InvoicePaymentMethodDeleteRepository $invoicePaymentMethodDeleteRepository = null;

    protected function createInvoicePaymentMethodDeleteRepository(): InvoicePaymentMethodDeleteRepository
    {
        return $this->invoicePaymentMethodDeleteRepository ?: InvoicePaymentMethodDeleteRepository::new();
    }

    /**
     * @param InvoicePaymentMethodDeleteRepository $invoicePaymentMethodDeleteRepository
     * @return static
     * @internal
     */
    public function setInvoicePaymentMethodDeleteRepository(InvoicePaymentMethodDeleteRepository $invoicePaymentMethodDeleteRepository): static
    {
        $this->invoicePaymentMethodDeleteRepository = $invoicePaymentMethodDeleteRepository;
        return $this;
    }
}
