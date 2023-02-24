<?php
/**
 * SAM-6356: Unite mutual logic in Auction Pdf Catalog module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 28, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\Catalog\Pdf\Path;

/**
 * Trait AuctionPdfCatalogPathResolverCreateTrait
 * @package Sam\Report\Auction\Catalog\Pdf
 */
trait AuctionPdfCatalogPathResolverCreateTrait
{
    /**
     * @var AuctionPdfCatalogPathResolver|null
     */
    protected ?AuctionPdfCatalogPathResolver $auctionPdfCatalogPathResolver = null;

    /**
     * @return AuctionPdfCatalogPathResolver
     */
    protected function createAuctionPdfCatalogPathResolver(): AuctionPdfCatalogPathResolver
    {
        return $this->auctionPdfCatalogPathResolver ?: AuctionPdfCatalogPathResolver::new();
    }

    /**
     * @param AuctionPdfCatalogPathResolver $auctionPdfCatalogPathResolver
     * @return $this
     * @internal
     */
    public function setAuctionPdfCatalogPathResolver(AuctionPdfCatalogPathResolver $auctionPdfCatalogPathResolver): static
    {
        $this->auctionPdfCatalogPathResolver = $auctionPdfCatalogPathResolver;
        return $this;
    }
}
