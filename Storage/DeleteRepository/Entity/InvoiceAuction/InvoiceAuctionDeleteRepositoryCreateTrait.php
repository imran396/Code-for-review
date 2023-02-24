<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\InvoiceAuction;

trait InvoiceAuctionDeleteRepositoryCreateTrait
{
    protected ?InvoiceAuctionDeleteRepository $invoiceAuctionDeleteRepository = null;

    protected function createInvoiceAuctionDeleteRepository(): InvoiceAuctionDeleteRepository
    {
        return $this->invoiceAuctionDeleteRepository ?: InvoiceAuctionDeleteRepository::new();
    }

    /**
     * @param InvoiceAuctionDeleteRepository $invoiceAuctionDeleteRepository
     * @return static
     * @internal
     */
    public function setInvoiceAuctionDeleteRepository(InvoiceAuctionDeleteRepository $invoiceAuctionDeleteRepository): static
    {
        $this->invoiceAuctionDeleteRepository = $invoiceAuctionDeleteRepository;
        return $this;
    }
}
