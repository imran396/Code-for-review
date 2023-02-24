<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\InvoiceAuction;

trait InvoiceAuctionWriteRepositoryAwareTrait
{
    protected ?InvoiceAuctionWriteRepository $invoiceAuctionWriteRepository = null;

    protected function getInvoiceAuctionWriteRepository(): InvoiceAuctionWriteRepository
    {
        if ($this->invoiceAuctionWriteRepository === null) {
            $this->invoiceAuctionWriteRepository = InvoiceAuctionWriteRepository::new();
        }
        return $this->invoiceAuctionWriteRepository;
    }

    /**
     * @param InvoiceAuctionWriteRepository $invoiceAuctionWriteRepository
     * @return static
     * @internal
     */
    public function setInvoiceAuctionWriteRepository(InvoiceAuctionWriteRepository $invoiceAuctionWriteRepository): static
    {
        $this->invoiceAuctionWriteRepository = $invoiceAuctionWriteRepository;
        return $this;
    }
}
