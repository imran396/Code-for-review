<?php
/**
 * SAM-5660: Auction lot ordering mysql query helper
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 31, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Order\Query;

/**
 * Trait AuctionLotOrderMysqlQueryBuilderCreateTrait
 * @package Sam\AuctionLot\Order\Query
 */
trait AuctionLotOrderMysqlQueryBuilderCreateTrait
{
    /**
     * @var AuctionLotOrderMysqlQueryBuilder|null
     */
    protected ?AuctionLotOrderMysqlQueryBuilder $auctionLotOrderMysqlQueryBuilder = null;

    /**
     * @return AuctionLotOrderMysqlQueryBuilder
     */
    protected function createAuctionLotOrderMysqlQueryBuilder(): AuctionLotOrderMysqlQueryBuilder
    {
        return $this->auctionLotOrderMysqlQueryBuilder ?: AuctionLotOrderMysqlQueryBuilder::new();
    }

    /**
     * @param AuctionLotOrderMysqlQueryBuilder $auctionLotOrderMysqlQueryBuilder
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionLotOrderMysqlQueryBuilder(AuctionLotOrderMysqlQueryBuilder $auctionLotOrderMysqlQueryBuilder): static
    {
        $this->auctionLotOrderMysqlQueryBuilder = $auctionLotOrderMysqlQueryBuilder;
        return $this;
    }
}
