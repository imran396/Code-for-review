<?php
/**
 * SAM-4616: Reports code refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/28/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\Entity;

use Auction;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Storage\Entity\Aggregate\AuctionAggregate;

/**
 * Trait FilterAuctionAwareTrait
 * @package Sam\Core\Filter\Entity
 */
trait FilterAuctionAwareTrait
{
    /** @var AuctionAggregate[] */
    protected array $filterAuctionAggregates = [];

    /**
     * Return auction id as int or ids as int[]
     * @return int|int[]|null
     */
    public function getFilterAuctionId(): int|array|null
    {
        $auctionIds = [];
        foreach ($this->getFilterAuctionAggregates() as $auctionAggregate) {
            $auctionIds[] = $auctionAggregate->getAuctionId();
        }
        $returnValue = empty($auctionIds) ? null
            : (count($auctionIds) === 1 ? $auctionIds[0] : $auctionIds);
        return $returnValue;
    }

    /**
     * @param int|int[]|null $auctionIds
     * @return static
     */
    public function filterAuctionId(int|array|null $auctionIds): static
    {
        $this->initFilterAuctionAggregates($auctionIds);
        return $this;
    }

    /**
     * @return Auction|Auction[]|null
     */
    public function getFilterAuction(): Auction|array|null
    {
        $auctions = [];
        foreach ($this->getFilterAuctionAggregates() as $auctionAggregate) {
            $auctions[] = $auctionAggregate->getAuction();
        }
        $returnValue = empty($auctions) ? null
            : (count($auctions) === 1 ? $auctions[0] : $auctions);
        return $returnValue;
    }

    /**
     * @param Auction|Auction[]|null $auctions
     * @return static
     */
    public function filterAuction(Auction|array|null $auctions = null): static
    {
        $this->initFilterAuctionAggregates($auctions);
        return $this;
    }

    // --- AuctionAggregate ---

    /**
     * @return AuctionAggregate[]
     */
    protected function getFilterAuctionAggregates(): array
    {
        return $this->filterAuctionAggregates;
    }

    /**
     * @param int|int[]|Auction|Auction[]|null $auctions
     */
    protected function initFilterAuctionAggregates(Auction|int|array|null $auctions): void
    {
        $this->filterAuctionAggregates = [];
        if (empty($auctions)) {
            return;
        }
        $auctions = is_array($auctions) ? $auctions : [$auctions];
        if (!($auctions[0] instanceof Auction)) {
            $auctionIds = ArrayCast::makeIntArray($auctions);
            foreach ($auctionIds as $auctionId) {
                $this->filterAuctionAggregates[$auctionId] = AuctionAggregate::new()->setAuctionId($auctionId);
            }
        } else {
            foreach ($auctions as $auction) {
                $this->filterAuctionAggregates[$auction->Id] = AuctionAggregate::new()->setAuction($auction);
            }
        }
    }
}
