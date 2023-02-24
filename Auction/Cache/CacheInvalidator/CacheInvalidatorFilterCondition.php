<?php
/**
 * SAM-6292: Move fields from auction_cache to auction_details_cache
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 03, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Cache\CacheInvalidator;


use Account;
use Auction;
use AuctionLotItem;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\DataSource\DbConnectionTrait;

/**
 * Contains filter conditions and methods to build SQL expressions to filter cache records that should be invalidated
 *
 * Class CacheInvalidatorFilterCondition
 * @package Sam\Auction\Cache\CacheInvalidator
 */
class CacheInvalidatorFilterCondition extends CustomizableClass
{
    use DbConnectionTrait;

    private string $filterEntityClass = '';
    private array $filterEntityIds = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int[] $auctionIds
     * @return static
     */
    public function filterAuctionId(array $auctionIds): static
    {
        $this->filterEntityClass = Auction::class;
        $this->filterEntityIds = array_filter(array_unique($auctionIds));
        return $this;
    }

    /**
     * @param int[] $accountIds
     * @return static
     */
    public function filterAccountId(array $accountIds): static
    {
        $this->filterEntityClass = Account::class;
        $this->filterEntityIds = array_filter(array_unique($accountIds));
        return $this;
    }

    /**
     * Filter by auction ids detected from auction lots
     * @param int[] $auctionLotIds
     * @return static
     */
    public function filterAuctionLotId(array $auctionLotIds): static
    {
        $this->filterEntityClass = AuctionLotItem::class;
        $this->filterEntityIds = array_filter(array_unique($auctionLotIds));
        return $this;
    }

    /**
     * Determine if the state is configured correctly and has all the required data
     * @return bool
     */
    public function isEmpty(): bool
    {
        return !$this->filterEntityClass || !$this->filterEntityIds;
    }

    /**
     * Build an expression for filtering by auction_id based on conditions.
     * Should be used in WHERE clause with the IN operator
     * @return string
     */
    public function buildExpression(): string
    {
        if ($this->isEmpty()) {
            return '';
        }

        return match ($this->filterEntityClass) {
            Auction::class => $this->buildFilterExpressionForAuctionIds(),
            Account::class => $this->buildFilterExpressionForAccountIds(),
            AuctionLotItem::class => $this->buildFilterExpressionForAuctionLotItemIds(),
            default => '',
        };
    }

    /**
     * @return string
     */
    private function buildFilterExpressionForAuctionIds(): string
    {
        $auctionIdsEscaped = [];
        foreach ($this->filterEntityIds as $auctionId) {
            $auctionIdsEscaped[] = $this->escape($auctionId);
        }
        $expression = implode(',', $auctionIdsEscaped);
        return $expression;
    }

    /**
     * @return string
     */
    private function buildFilterExpressionForAccountIds(): string
    {
        $accountIdsEscaped = [];
        foreach ($this->filterEntityIds as $accountId) {
            $accountIdsEscaped[] = $this->escape($accountId);
        }
        $accountIdList = implode(',', $accountIdsEscaped);
        $expression = "SELECT a.id FROM auction a WHERE a.account_id IN ({$accountIdList})";
        return $expression;
    }

    /**
     * @return string
     */
    private function buildFilterExpressionForAuctionLotItemIds(): string
    {
        $auctionLotIdsEscaped = [];
        foreach ($this->filterEntityIds as $auctionLotId) {
            $auctionLotIdsEscaped[] = $this->escape($auctionLotId);
        }
        $auctionLotIdList = implode(',', $auctionLotIdsEscaped);
        $expression = "SELECT ali.auction_id FROM auction_lot_item ali WHERE ali.id IN ({$auctionLotIdList})";
        return $expression;
    }
}
