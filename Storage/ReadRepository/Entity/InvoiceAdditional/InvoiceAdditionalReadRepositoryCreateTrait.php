<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\InvoiceAdditional;

trait InvoiceAdditionalReadRepositoryCreateTrait
{
    protected ?InvoiceAdditionalReadRepository $invoiceAdditionalReadRepository = null;

    protected function createInvoiceAdditionalReadRepository(): InvoiceAdditionalReadRepository
    {
        return $this->invoiceAdditionalReadRepository ?: InvoiceAdditionalReadRepository::new();
    }

    /**
     * @param InvoiceAdditionalReadRepository $invoiceAdditionalReadRepository
     * @return static
     * @internal
     */
    public function setInvoiceAdditionalReadRepository(InvoiceAdditionalReadRepository $invoiceAdditionalReadRepository): static
    {
        $this->invoiceAdditionalReadRepository = $invoiceAdditionalReadRepository;
        return $this;
    }
}
