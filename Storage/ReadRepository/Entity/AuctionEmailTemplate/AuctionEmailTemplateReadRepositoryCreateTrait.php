<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionEmailTemplate;

trait AuctionEmailTemplateReadRepositoryCreateTrait
{
    protected ?AuctionEmailTemplateReadRepository $auctionEmailTemplateReadRepository = null;

    protected function createAuctionEmailTemplateReadRepository(): AuctionEmailTemplateReadRepository
    {
        return $this->auctionEmailTemplateReadRepository ?: AuctionEmailTemplateReadRepository::new();
    }

    /**
     * @param AuctionEmailTemplateReadRepository $auctionEmailTemplateReadRepository
     * @return static
     * @internal
     */
    public function setAuctionEmailTemplateReadRepository(AuctionEmailTemplateReadRepository $auctionEmailTemplateReadRepository): static
    {
        $this->auctionEmailTemplateReadRepository = $auctionEmailTemplateReadRepository;
        return $this;
    }
}
