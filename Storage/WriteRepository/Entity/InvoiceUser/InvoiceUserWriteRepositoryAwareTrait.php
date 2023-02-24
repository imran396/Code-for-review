<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\InvoiceUser;

trait InvoiceUserWriteRepositoryAwareTrait
{
    protected ?InvoiceUserWriteRepository $invoiceUserWriteRepository = null;

    protected function getInvoiceUserWriteRepository(): InvoiceUserWriteRepository
    {
        if ($this->invoiceUserWriteRepository === null) {
            $this->invoiceUserWriteRepository = InvoiceUserWriteRepository::new();
        }
        return $this->invoiceUserWriteRepository;
    }

    /**
     * @param InvoiceUserWriteRepository $invoiceUserWriteRepository
     * @return static
     * @internal
     */
    public function setInvoiceUserWriteRepository(InvoiceUserWriteRepository $invoiceUserWriteRepository): static
    {
        $this->invoiceUserWriteRepository = $invoiceUserWriteRepository;
        return $this;
    }
}
