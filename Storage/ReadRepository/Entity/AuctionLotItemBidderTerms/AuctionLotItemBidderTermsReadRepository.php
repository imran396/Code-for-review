<?php
/**
 * General repository for AuctionLotItemBidderTerms Parameters entity
 *
 * SAM-3653: AuctionLotItem repository https://bidpath.atlassian.net/browse/SAM-3653
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           03 Apr, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * Usage example:
 * $auctionLotItemBidderTermsRepository = \Sam\Storage\ReadRepository\Entity\AuctionLotItemBidderTerms\AuctionLotItemBidderTermsReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterAuctionId($auctionIds);
 * $isFound = $auctionLotItemBidderTermsRepository->exist();
 * $count = $auctionLotItemBidderTermsRepository->count();
 * $item = $auctionLotItemBidderTermsRepository->loadEntity();
 * $items = $auctionLotItemBidderTermsRepository->loadEntities();
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionLotItemBidderTerms;

/**
 * Class AuctionLotItemBidderTermsReadRepository
 */
class AuctionLotItemBidderTermsReadRepository extends AbstractAuctionLotItemBidderTermsReadRepository
{
    protected array $joins = [
        'auction_bidder' => 'JOIN auction_bidder aub ON alibt.user_id = aub.user_id',
        'auction_lot_item' => 'JOIN auction_lot_item ali ON ali.lot_item_id = alibt.lot_item_id',
        'lot_item' => 'JOIN lot_item li ON li.id = alibt.lot_item_id',
        'user' => 'JOIN `user` u ON u.id = alibt.user_id',
        'user_info' => 'JOIN user_info ui ON alibt.user_id = ui.user_id',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join `auction_bidder` table
     * @return static
     */
    public function joinAuctionBidder(): static
    {
        $this->join('auction_bidder');
        return $this;
    }

    /**
     * Left join auction_bidder table
     * Define filtering by aub.auction_id
     * @param int|int[] $auctionLotIds
     * @return static
     */
    public function joinAuctionBidderFilterAuctionId(int|array|null $auctionLotIds): static
    {
        $this->joinAuctionBidder();
        $this->filterArray('aub.auction_id', $auctionLotIds);
        return $this;
    }

    /**
     * Left join auction_bidder table
     * Define ORDER BY aub.bidder_num
     * @param bool $ascending
     * @return static
     */
    public function joinAuctionBidderOrderByBidderNum(bool $ascending = true): static
    {
        $this->joinAuctionBidder();
        $this->order('aub.bidder_num', $ascending);
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
     * Left join auction_lot_item table
     * Define filtering by ali.auction_id
     * @param int|int[] $auctionLotIds
     * @return static
     */
    public function joinAuctionLotItemFilterAuctionId(int|array|null $auctionLotIds): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('ali.auction_id', $auctionLotIds);
        return $this;
    }

    /**
     * Left join auction_lot_item table
     * Define ORDER BY ali.lot_num as lot_num
     * @param bool $ascending
     * @return static
     */
    public function joinAuctionLotItemOrderByLotNum(bool $ascending = true): static
    {
        $this->joinAuctionLotItem();
        $this->order('ali.lot_num', $ascending);
        return $this;
    }

    /**
     * Left join auction_lot_item table
     * Define ORDER BY ali.lot_numb_ext as lot_num_ext
     * @param bool $ascending
     * @return static
     */
    public function joinAuctionLotItemOrderByLotNumExt(bool $ascending = true): static
    {
        $this->joinAuctionLotItem();
        $this->order('ali.lot_num_ext', $ascending);
        return $this;
    }

    /**
     * Left join auction_lot_item table
     * Define ORDER BY ali.lot_numb_prefix as lot_num_prefix
     * @param bool $ascending
     * @return static
     */
    public function joinAuctionLotItemOrderByLotNumPrefix(bool $ascending = true): static
    {
        $this->joinAuctionLotItem();
        $this->order('ali.lot_num_prefix', $ascending);
        return $this;
    }

    /**
     * Left join auction_lot_item table
     * Define ORDER BY ali.terms_and_conditions
     * @param bool $ascending
     * @return static
     */
    public function joinAuctionLotItemOrderByTermsAndConditions(bool $ascending = true): static
    {
        $this->joinAuctionLotItem();
        $this->order('ali.terms_and_conditions', $ascending);
        return $this;
    }

    /**
     * Left join lot_item table
     * @return static
     */
    public function joinLotItem(): static
    {
        $this->join('lot_item');
        return $this;
    }

    /**
     * Left join lot_item table
     * Define ORDER BY li.item_num
     * @param bool $ascending
     * @return static
     */
    public function joinLotItemOrderByItemNum(bool $ascending = true): static
    {
        $this->joinLotItem();
        $this->order('li.item_num', $ascending);
        return $this;
    }

    /**
     * Left join lot_item table
     * Define ORDER BY li.item_num_ext
     * @param bool $ascending
     * @return static
     */
    public function joinLotItemOrderByItemNumberExt(bool $ascending = true): static
    {
        $this->joinLotItem();
        $this->order('li.item_num_ext', $ascending);
        return $this;
    }

    /**
     * Left join lot_item table
     * Define ORDER BY li.name
     * @param bool $ascending
     * @return static
     */
    public function joinLotItemOrderByName(bool $ascending = true): static
    {
        $this->joinLotItem();
        $this->order('li.name', $ascending);
        return $this;
    }

    /**
     * Left join `user` table
     * @return static
     */
    public function joinUser(): static
    {
        $this->join('user');
        return $this;
    }

    /**
     * Left join user table
     * Define ORDER BY u.customer_no
     * @param bool $ascending
     * @return static
     */
    public function joinUserOrderByCustomerNo(bool $ascending = true): static
    {
        $this->joinUser();
        $this->order('u.customer_no', $ascending);
        return $this;
    }

    /**
     * Left join user table
     * Define ORDER BY u.username
     * @param bool $ascending
     * @return static
     */
    public function joinUserOrderByUsername(bool $ascending = true): static
    {
        $this->joinUser();
        $this->order('u.username', $ascending);
        return $this;
    }

    /**
     * Left join user table
     * Define ORDER BY u.email
     * @param bool $ascending
     * @return static
     */
    public function joinUserOrderByEmail(bool $ascending = true): static
    {
        $this->joinUser();
        $this->order('u.email', $ascending);
        return $this;
    }

    /**
     * Left join user_info table
     * @return static
     */
    public function joinUserInfo(): static
    {
        $this->join('user_info');
        return $this;
    }

    /**
     * Left join user_info table
     * Define ORDER BY ui.first_name
     * @param bool $ascending
     * @return static
     */
    public function joinUserInfoOrderByFirstName(bool $ascending = true): static
    {
        $this->joinUserInfo();
        $this->order('ui.first_name', $ascending);
        return $this;
    }

    /**
     * Left join user_info table
     * Define ORDER BY ui.last_name
     * @param bool $ascending
     * @return static
     */
    public function joinUserInfoOrderByLastName(bool $ascending = true): static
    {
        $this->joinUserInfo();
        $this->order('ui.last_name', $ascending);
        return $this;
    }
}
