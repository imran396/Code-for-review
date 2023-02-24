<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\InvoiceAuction;

trait InvoiceAuctionReadRepositoryCreateTrait
{
    protected ?InvoiceAuctionReadRepository $invoiceAuctionReadRepository = null;

    protected function createInvoiceAuctionReadRepository(): InvoiceAuctionReadRepository
    {
        return $this->invoiceAuctionReadRepository ?: InvoiceAuctionReadRepository::new();
    }

    /**
     * @param InvoiceAuctionReadRepository $invoiceAuctionReadRepository
     * @return static
     * @internal
     */
    public function setInvoiceAuctionReadRepository(InvoiceAuctionReadRepository $invoiceAuctionReadRepository): static
    {
        $this->invoiceAuctionReadRepository = $invoiceAuctionReadRepository;
        return $this;
    }
}
