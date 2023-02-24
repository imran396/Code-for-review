<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionEmailTemplate;

trait AuctionEmailTemplateDeleteRepositoryCreateTrait
{
    protected ?AuctionEmailTemplateDeleteRepository $auctionEmailTemplateDeleteRepository = null;

    protected function createAuctionEmailTemplateDeleteRepository(): AuctionEmailTemplateDeleteRepository
    {
        return $this->auctionEmailTemplateDeleteRepository ?: AuctionEmailTemplateDeleteRepository::new();
    }

    /**
     * @param AuctionEmailTemplateDeleteRepository $auctionEmailTemplateDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionEmailTemplateDeleteRepository(AuctionEmailTemplateDeleteRepository $auctionEmailTemplateDeleteRepository): static
    {
        $this->auctionEmailTemplateDeleteRepository = $auctionEmailTemplateDeleteRepository;
        return $this;
    }
}
