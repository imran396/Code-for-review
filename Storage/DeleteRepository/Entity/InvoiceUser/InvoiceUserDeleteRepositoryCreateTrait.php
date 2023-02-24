<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\InvoiceUser;

trait InvoiceUserDeleteRepositoryCreateTrait
{
    protected ?InvoiceUserDeleteRepository $invoiceUserDeleteRepository = null;

    protected function createInvoiceUserDeleteRepository(): InvoiceUserDeleteRepository
    {
        return $this->invoiceUserDeleteRepository ?: InvoiceUserDeleteRepository::new();
    }

    /**
     * @param InvoiceUserDeleteRepository $invoiceUserDeleteRepository
     * @return static
     * @internal
     */
    public function setInvoiceUserDeleteRepository(InvoiceUserDeleteRepository $invoiceUserDeleteRepository): static
    {
        $this->invoiceUserDeleteRepository = $invoiceUserDeleteRepository;
        return $this;
    }
}
