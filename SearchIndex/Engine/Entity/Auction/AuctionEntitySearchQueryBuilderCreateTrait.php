<?php
/**
 * SAM-6474: Move full-text search query building and queue management logic to \Sam\SearchIndex namespace
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\SearchIndex\Engine\Entity\Auction;

/**
 * Trait AuctionEntitySearchQueryBuilderCreateTrait
 * @package Sam\SearchIndex\Engine\Entity\Auction
 */
trait AuctionEntitySearchQueryBuilderCreateTrait
{
    protected ?AuctionEntitySearchQueryBuilder $auctionEntitySearchQueryBuilder = null;

    /**
     * @return AuctionEntitySearchQueryBuilder
     */
    protected function createAuctionEntitySearchQueryBuilder(): AuctionEntitySearchQueryBuilder
    {
        return $this->auctionEntitySearchQueryBuilder ?: AuctionEntitySearchQueryBuilder::new();
    }

    /**
     * @param AuctionEntitySearchQueryBuilder $auctionEntitySearchQueryBuilder
     * @return $this
     * @internal
     */
    public function setAuctionEntitySearchQueryBuilder(AuctionEntitySearchQueryBuilder $auctionEntitySearchQueryBuilder): static
    {
        $this->auctionEntitySearchQueryBuilder = $auctionEntitySearchQueryBuilder;
        return $this;
    }
}
