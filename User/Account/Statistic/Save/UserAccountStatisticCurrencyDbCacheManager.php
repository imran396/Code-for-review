<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           7/8/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Account\Statistic\Save;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserWatchlist\UserWatchlistReadRepository;
use Sam\Storage\ReadRepository\Entity\UserWatchlist\UserWatchlistReadRepositoryCreateTrait;

/**
 * Class UserAccountStatisticCurrencyDbCacheManager
 * @package Sam\User\Account\Statistic\Save
 */
class UserAccountStatisticCurrencyDbCacheManager extends CustomizableClass
{
    use AbsenteeBidReadRepositoryCreateTrait;
    use BidTransactionReadRepositoryCreateTrait;
    use CurrencyLoaderAwareTrait;
    use LotItemReadRepositoryCreateTrait;
    use UserWatchlistReadRepositoryCreateTrait;

    protected ?string $primaryCurrencySign = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function buildQueryLotsConsignedSoldAmt(int $accountId): string
    {
        return $this->createLotItemReadRepository()
            ->select(['SUM(IFNULL(li.hammer_price, 0)) AS hammer_price'])
            ->joinCurrencyFilterCurrencySign($this->getPrimaryCurrencySign())
            ->filterAccountId($accountId)
            ->inlineCondition('li.consignor_id = u.id')
            ->getResultQuery();
    }

    public function buildQueryLotsWonAmt(int $accountId): string
    {
        return $this->createLotItemReadRepository()
            ->select(['SUM(IFNULL(li.hammer_price, 0)) AS hammer_price'])
            ->joinCurrencyFilterCurrencySign($this->getPrimaryCurrencySign())
            ->filterAccountId($accountId)
            ->inlineCondition('li.winning_bidder_id = u.id')
            ->getResultQuery();
    }

    public function buildQueryLotsBidOnAmt(int $accountId): string
    {
        $subqueryBidTransaction = $this->createBidTransactionReadRepository()
            ->select(['MAX(bt.bid) AS high_bid', 'bt.user_id AS bid_transaction_user_id'])
            ->innerJoinAuctionLotItemFilterAccountId($accountId)
            ->defineLotItemJoinByAuctionLotItem()
            ->innerJoinLotItem()
            ->innerJoinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->innerJoinCurrencyFilterCurrencySign($this->getPrimaryCurrencySign())
            ->filterDeleted(false)
            ->filterFailed(false)
            ->groupByLotItemId()
            ->groupByAuctionId()
            ->groupByUserId()
            ->getResultQuery();

        return "SELECT SUM(high_bid) FROM ({$subqueryBidTransaction}) AS high_bids WHERE bid_transaction_user_id = u.id";
    }

    public function buildQueryWatchlistItemsWonAmt(int $accountId): string
    {
        return $this->createLotItemReadRepository()
            ->select(['SUM(IFNULL(li.hammer_price, 0)) AS hammer_price'])
            ->joinCurrencyFilterCurrencySign($this->getPrimaryCurrencySign())
            ->joinUserWatchlist()
            ->filterAccountId($accountId)
            ->inlineCondition('li.winning_bidder_id = u.id')
            ->getResultQuery();
    }

    public function buildQueryWatchlistItemsBidAmt(int $accountId): string
    {
        $subqueryAbsenteeBid = $this->createAbsenteeBidReadRepository()
            ->filterMaxBidGreater(0)
            ->inlineCondition('ab.auction_id = uw.auction_id AND ab.lot_item_id = uw.lot_item_id')
            ->getCountQuery();

        $subqueryBidTransaction = $this->createBidTransactionReadRepository()
            ->filterDeleted(false)
            ->filterFailed(false)
            ->inlineCondition('bt.auction_id = uw.auction_id AND bt.lot_item_id = uw.lot_item_id')
            ->getCountQuery();

        return $this->prepareWatchlistRepository($accountId)
            ->select(['SUM(IFNULL(li.hammer_price, 0)) AS hammer_price'])
            ->joinCurrencyFilterCurrencySign($this->getPrimaryCurrencySign())
            ->inlineCondition("(({$subqueryAbsenteeBid}) > 0 OR ({$subqueryBidTransaction}) > 0)")
            ->inlineCondition('uw.user_id = u.id')
            ->groupByCurrencySign()
            ->getResultQuery();
    }

    public function getPrimaryCurrencySign(): string
    {
        if ($this->primaryCurrencySign === null) {
            $this->primaryCurrencySign = $this->getCurrencyLoader()->findPrimaryCurrencySign();
        }
        return $this->primaryCurrencySign;
    }

    public function setPrimaryCurrencySign(string $primaryCurrencySign): static
    {
        $this->primaryCurrencySign = $primaryCurrencySign;
        return $this;
    }

    protected function prepareWatchlistRepository(int $accountId): UserWatchlistReadRepository
    {
        return $this->createUserWatchlistReadRepository()
            ->joinAuctionFilterAccountId($accountId)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->joinLotItemFilterActive(true);
    }
}
