<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\InvoiceAdditional;

trait InvoiceAdditionalDeleteRepositoryCreateTrait
{
    protected ?InvoiceAdditionalDeleteRepository $invoiceAdditionalDeleteRepository = null;

    protected function createInvoiceAdditionalDeleteRepository(): InvoiceAdditionalDeleteRepository
    {
        return $this->invoiceAdditionalDeleteRepository ?: InvoiceAdditionalDeleteRepository::new();
    }

    /**
     * @param InvoiceAdditionalDeleteRepository $invoiceAdditionalDeleteRepository
     * @return static
     * @internal
     */
    public function setInvoiceAdditionalDeleteRepository(InvoiceAdditionalDeleteRepository $invoiceAdditionalDeleteRepository): static
    {
        $this->invoiceAdditionalDeleteRepository = $invoiceAdditionalDeleteRepository;
        return $this;
    }
}
