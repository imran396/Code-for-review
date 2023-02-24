<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\InvoiceUser;

trait InvoiceUserReadRepositoryCreateTrait
{
    protected ?InvoiceUserReadRepository $invoiceUserReadRepository = null;

    protected function createInvoiceUserReadRepository(): InvoiceUserReadRepository
    {
        return $this->invoiceUserReadRepository ?: InvoiceUserReadRepository::new();
    }

    /**
     * @param InvoiceUserReadRepository $invoiceUserReadRepository
     * @return static
     * @internal
     */
    public function setInvoiceUserReadRepository(InvoiceUserReadRepository $invoiceUserReadRepository): static
    {
        $this->invoiceUserReadRepository = $invoiceUserReadRepository;
        return $this;
    }
}
