<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\InvoiceAdditional;

trait InvoiceAdditionalWriteRepositoryAwareTrait
{
    protected ?InvoiceAdditionalWriteRepository $invoiceAdditionalWriteRepository = null;

    protected function getInvoiceAdditionalWriteRepository(): InvoiceAdditionalWriteRepository
    {
        if ($this->invoiceAdditionalWriteRepository === null) {
            $this->invoiceAdditionalWriteRepository = InvoiceAdditionalWriteRepository::new();
        }
        return $this->invoiceAdditionalWriteRepository;
    }

    /**
     * @param InvoiceAdditionalWriteRepository $invoiceAdditionalWriteRepository
     * @return static
     * @internal
     */
    public function setInvoiceAdditionalWriteRepository(InvoiceAdditionalWriteRepository $invoiceAdditionalWriteRepository): static
    {
        $this->invoiceAdditionalWriteRepository = $invoiceAdditionalWriteRepository;
        return $this;
    }
}
