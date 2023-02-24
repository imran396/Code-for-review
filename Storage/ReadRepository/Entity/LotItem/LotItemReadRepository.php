<?php
/**
 * General repository for LotItem entity
 *
 * SAM-3627: LotItem general repository class https://bidpath.atlassian.net/browse/SAM-3627
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           14 Dec, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage example:
 * $repository = \Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterActive(true);
 * $isFound = $repository->exist();
 * $count = $repository->count();
 * $item = $repository->loadEntity();
 * $items = $repository->loadEntities();
 */

namespace Sam\Storage\ReadRepository\Entity\LotItem;

use Sam\Core\Constants;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;

/**
 * Class LotItemReadRepository
 * @package Sam\Storage\ReadRepository\Entity\LotItem
 */
class LotItemReadRepository extends AbstractLotItemReadRepository
{
    use CurrencyLoaderAwareTrait;

    protected array $joins = [
        'absentee_bid' => 'JOIN absentee_bid ab ON ab.lot_item_id = li.id AND ab.lot_item_id = ali.lot_item_id AND ab.auction_id = ali.auction_id',
        'account' => 'JOIN account acc ON acc.id = li.account_id',
        'auction_by_auction_id' => 'JOIN auction a ON a.id = li.auction_id',
        'auction_lot_item' => 'JOIN auction_lot_item ali ON ali.lot_item_id = li.id',
        'auction_bidder' => 'JOIN auction_bidder aub ON aub.auction_id = ali.auction_id', // by auction_lot_item
        'bid_transaction' => 'JOIN bid_transaction bt ON bt.lot_item_id = li.id AND bt.lot_item_id = ali.lot_item_id AND bt.auction_id = ali.auction_id',
        'entity_sync' => 'JOIN entity_sync esync ON (li.id = esync.entity_id AND esync.entity_type = ' . Constants\EntitySync::TYPE_LOT_ITEM . ')',
        'user' => 'JOIN `user` AS u ON u.id = aub.user_id',  // by auction_lot_item -> auction_bidder
        'user_account' => 'JOIN user_account ua ON ua.user_id = u.id', // by user
        'user_info' => 'JOIN user_info AS ui ON u.id = ui.user_id', // by auction_lot_item -> auction_bidder -> user
        'user_watchlist' => 'JOIN user_watchlist AS uw ON uw.lot_item_id = li.id',
        'currency' => 'JOIN currency curr ON curr.id = a.currency',
        'consignor_commission' => 'JOIN consignor_commission_fee ccf_cc ON ccf_cc.id = li.consignor_commission_id',
        'consignor_sold_fee' => 'JOIN consignor_commission_fee ccf_csf ON ccf_csf.id = li.consignor_sold_fee_id',
        'consignor_unsold_fee' => 'JOIN consignor_commission_fee ccf_cuf ON ccf_cuf.id = li.consignor_unsold_fee_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Group by c.currency_sign
     * @return static
     */
    public function joinCurrencyGroupCurrencySign(): static
    {
        $this->joinCurrencySelectCurrencySign();
        $this->group('currency_sign');
        return $this;
    }

    /**
     * Group by u.id
     * @return static
     */
    public function joinUserGroupByUserId(): static
    {
        $this->joinUser();
        $this->group('u.id');
        return $this;
    }

    /**
     * Join 'account' table
     * @return static
     */
    public function joinAccount(): static
    {
        $this->join('account');
        return $this;
    }

    /**
     * Join 'auction_bidder' table by auction_lot_item.auction_id
     * @return static
     */
    public function joinAuctionBidder(): static
    {
        $this->joinAuctionLotItem();
        $this->join('auction_bidder');
        return $this;
    }

    /**
     * Join 'auction_bidder' table by auction_lot_item.auction_id and filter by auction id
     * @param $auctionId
     * @return $this
     */
    public function joinAuctionBidderFilterAuctionId($auctionId): static
    {
        $this->joinAuctionBidder();
        $this->filterArray('aub.auction_id', $auctionId);
        return $this;
    }

    /**
     * Join 'user' table by auction_bidder.user_id
     * @return static
     */
    public function joinUser(): static
    {
        $this->joinAuctionBidder();
        $this->join('user');
        return $this;
    }

    /**
     * Join 'user_info' table by user.id
     * @return static
     */
    public function joinUserInfo(): static
    {
        $this->joinUser();
        $this->join('user_info');
        return $this;
    }

    /**
     * join 'user_account' table by user.id
     * @return static
     */
    public function joinUserAccount(): static
    {
        $this->joinUser();
        $this->join('user_account');
        return $this;
    }

    public function joinUserWatchlist(): static
    {
        $this->innerJoin('user_watchlist');
        return $this;
    }

    /**
     * Define filtering by account.active
     * @param bool|bool[] $active
     * @return static
     */
    public function joinAccountFilterActive(bool|array|null $active): static
    {
        $this->joinAccount();
        $this->filterArray('acc.active', $active);
        return $this;
    }

    /**
     * Join `auction_lot_item` table
     * @return static
     */
    public function joinAuctionLotItem(): static
    {
        $this->join('auction_lot_item');
        return $this;
    }

    /**
     * Left join auction table
     * @return static
     */
    public function joinAuctionByAuctionId(): static
    {
        $this->join('auction_by_auction_id');
        return $this;
    }

    /**
     * Join currency table
     * @return static
     */
    public function joinCurrency(): static
    {
        $this->joinAuctionByAuctionId();
        $this->join('currency');
        return $this;
    }

    /**
     * Define filtering by auction_lot_item.auction_id
     * @param int|int[] $auctionId
     * @return static
     */
    public function joinAuctionLotItemFilterAuctionId(int|array|null $auctionId): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('ali.auction_id', $auctionId);
        return $this;
    }

    public function joinCurrencyFilterCurrencySign(string $currencySign): static
    {
        $this->joinCurrency();
        $this->filterArray('curr.sign', [$currencySign, null]);
        return $this;
    }

    /**
     * Add select statement for lot item currency_sign
     * @return $this
     */
    public function joinCurrencySelectCurrencySign(): static
    {
        $this->joinCurrency();
        $primaryCurrencySign = $this->getCurrencyLoader()->findPrimaryCurrencySign();
        $this->addSelect(
            'IF (li.auction_id > 0, curr.sign,' . $this->escape($primaryCurrencySign) . ') AS currency_sign'
        );
        return $this;
    }

    public function joinAbsenteeBid(): static
    {
        $this->join('absentee_bid');
        return $this;
    }

    /**
     * Define filtering by auction_lot_item.lot_status_id
     * @param int|int[] $lotStatusId
     * @return static
     */
    public function joinAuctionLotItemFilterLotStatusId(int|array|null $lotStatusId): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('ali.lot_status_id', $lotStatusId);
        return $this;
    }

    /**
     * @param string $lotNum
     * @return static
     */
    public function joinAuctionLotItemLikeLotNum(string $lotNum): static
    {
        $this->joinAuctionLotItem();
        $this->like('ali.lot_num', "%{$lotNum}%");
        return $this;
    }

    /**
     * @param string $lotNumExt
     * @return static
     */
    public function joinAuctionLotItemLikeLotNumExt(string $lotNumExt): static
    {
        $this->joinAuctionLotItem();
        $this->like('ali.lot_num_ext', "%{$lotNumExt}%");
        return $this;
    }

    /**
     * @param string $lotNumPrefix
     * @return static
     */
    public function joinAuctionLotItemLikeLotNumPrefix(string $lotNumPrefix): static
    {
        $this->joinAuctionLotItem();
        $this->like('ali.lot_num_prefix', "%{$lotNumPrefix}%");
        return $this;
    }

    public function joinBidTransaction(): static
    {
        $this->join('bid_transaction');
        return $this;
    }

    /**
     * Join `entity_sync` table
     * @return static
     */
    public function joinLotItemSync(): static
    {
        $this->join('entity_sync');
        return $this;
    }

    /**
     * @param string|string[] $key
     * @return static
     */
    public function joinLotItemSyncFilterKey(string|array|null $key): static
    {
        $this->joinLotItemSync();
        $this->filterArray('esync.key', $key);
        return $this;
    }

    /**
     * @param int|int[] $namespaceId
     * @return static
     */
    public function joinLotItemSyncFilterSyncNamespaceId(int|array|null $namespaceId): static
    {
        $this->joinLotItemSync();
        $this->filterArray('esync.sync_namespace_id', $namespaceId);
        return $this;
    }

    /**
     * Define filtering by user.user_status_id
     * @param int|int[] $userStatus
     * @return static
     */
    public function joinUserFilterUserStatusId(int|array|null $userStatus): static
    {
        $this->joinUser();
        $this->filterArray('u.user_status_id', $userStatus);
        return $this;
    }

    /**
     * @param string $itemNum
     * @return static
     */
    public function likeItemNum(string $itemNum): static
    {
        $this->like('CAST(li.item_num as CHAR)', "%{$itemNum}%");
        return $this;
    }

    public function joinConsignorCommission(): static
    {
        $this->join('consignor_commission');
        return $this;
    }

    public function joinConsignorCommissionFilterActive(bool|array $active): static
    {
        $this->joinConsignorCommission();
        $this->filterArray('ccf_cc.active', $active);
        return $this;
    }

    public function joinConsignorSoldFee(): static
    {
        $this->join('consignor_sold_fee');
        return $this;
    }

    public function joinConsignorSoldFeeFilterActive(bool|array $active): static
    {
        $this->joinConsignorSoldFee();
        $this->filterArray('ccf_csf.active', $active);
        return $this;
    }

    public function joinConsignorUnsoldFee(): static
    {
        $this->join('consignor_unsold_fee');
        return $this;
    }

    public function joinConsignorUnsoldFeeFilterActive(bool|array $active): static
    {
        $this->joinConsignorUnsoldFee();
        $this->filterArray('ccf_cuf.active', $active);
        return $this;
    }
}
