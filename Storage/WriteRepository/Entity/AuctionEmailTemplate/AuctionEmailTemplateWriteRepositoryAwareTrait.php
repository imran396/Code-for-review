<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionEmailTemplate;

trait AuctionEmailTemplateWriteRepositoryAwareTrait
{
    protected ?AuctionEmailTemplateWriteRepository $auctionEmailTemplateWriteRepository = null;

    protected function getAuctionEmailTemplateWriteRepository(): AuctionEmailTemplateWriteRepository
    {
        if ($this->auctionEmailTemplateWriteRepository === null) {
            $this->auctionEmailTemplateWriteRepository = AuctionEmailTemplateWriteRepository::new();
        }
        return $this->auctionEmailTemplateWriteRepository;
    }

    /**
     * @param AuctionEmailTemplateWriteRepository $auctionEmailTemplateWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionEmailTemplateWriteRepository(AuctionEmailTemplateWriteRepository $auctionEmailTemplateWriteRepository): static
    {
        $this->auctionEmailTemplateWriteRepository = $auctionEmailTemplateWriteRepository;
        return $this;
    }
}
