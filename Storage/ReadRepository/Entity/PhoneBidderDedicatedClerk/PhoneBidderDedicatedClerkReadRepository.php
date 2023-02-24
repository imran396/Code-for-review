<?php
/**
 * General repository for PhoneBidderDedicatedClerk entity
 *
 * SAM-3680 : Bidder and consignor related repositories https://bidpath.atlassian.net/browse/SAM-3680
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           21 Apr, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of users filtered by criteria
 * $phoneBidderDedicatedClerkRepository = \Sam\Storage\ReadRepository\Entity\PhoneBidderDedicatedClerk\PhoneBidderDedicatedClerkReadRepository::new()
 *     ->filterAssignedClerk($assignedClerk)          // single value passed as argument
 *     ->filterAuctionId($auctionIds)      // array passed as argument
 * $isFound = $phoneBidderDedicatedClerkRepository->exist();
 * $count = $phoneBidderDedicatedClerkRepository->count();
 * $items = $phoneBidderDedicatedClerkRepository->loadEntities();
 *
 * // Sample2. Load single user
 * $phoneBidderDedicatedClerkRepository = \Sam\Storage\ReadRepository\Entity\PhoneBidderDedicatedClerk\PhoneBidderDedicatedClerkReadRepository::new()
 *     ->filterId(1);
 * $item = $phoneBidderDedicatedClerkRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\PhoneBidderDedicatedClerk;

/**
 * Class PhoneBidderDedicatedClerkReadRepository
 */
class PhoneBidderDedicatedClerkReadRepository extends AbstractPhoneBidderDedicatedClerkReadRepository
{
    protected array $joins = [
        'absentee_bid' => 'JOIN absentee_bid ab ON ab.auction_id = pbdc.auction_id AND ab.user_id = pbdc.bidder_id',
        'auction_lot_item' => 'JOIN auction_lot_item ali ON ali.auction_id = ab.auction_id AND ab.lot_item_id = ali.lot_item_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join `absentee_bid` table
     * @return static
     */
    public function joinAbsenteeBid(): static
    {
        $this->join('absentee_bid');
        return $this;
    }

    /**
     * Join absentee_bid table
     * Define filtering by ab.auction_id
     * @param int|int[] $auctionIds
     * @return static
     */
    public function joinAbsenteeBidFilterAuctionId(int|array|null $auctionIds): static
    {
        $this->joinAbsenteeBid();
        $this->filterArray('ab.auction_id', $auctionIds);
        return $this;
    }

    /**
     * Join absentee_bid table
     * Define filtering by ab.lot_item_id
     * @param int|int[] $lotItemIds
     * @return static
     */
    public function joinAbsenteeBidFilterLotItemId(int|array|null $lotItemIds): static
    {
        $this->joinAbsenteeBid();
        $this->filterArray('ab.lot_item_id', $lotItemIds);
        return $this;
    }

    /**
     * Join absentee_bid table
     * Define filtering by ab.bid_type
     * @param int|int[] $bidTypes
     * @return static
     */
    public function joinAbsenteeBidFilterBidType(int|array|null $bidTypes): static
    {
        $this->joinAbsenteeBid();
        $this->filterArray('ab.bid_type', $bidTypes);
        return $this;
    }

    /**
     * Join `auction_lot_item` table
     * @return static
     */
    public function joinAuctionLotItem(): static
    {
        $this->joinAbsenteeBid();
        $this->join('auction_lot_item');
        return $this;
    }

    /**
     * Join auction_lot_item table
     * Define filtering by ali.lot_item_id
     * @param int|int[] $lotItemIds
     * @return static
     */
    public function joinAuctionLotItemFilterLotItemId(int|array|null $lotItemIds): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('ali.lot_item_id', $lotItemIds);
        return $this;
    }

    /**
     * Join auction_lot_item table
     * Define filtering by ali.lot_status_id
     * @param int|int[] $lotStatusIds
     * @return static
     */
    public function joinAuctionLotItemFilterLotStatusId(int|array|null $lotStatusIds): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('ali.lot_status_id', $lotStatusIds);
        return $this;
    }

    /**
     * Join auction_lot_item table
     * Define filtering by CONCAT(IFNULL(ali.lot_num_prefix,''),ali.lot_num,IFNULL(ali.lot_num_ext,''))
     * @param string|string[] $lotNL Next Lot
     * @return static
     */
    public function joinAuctionLotItemFilterLotNL(string|array $lotNL): static
    {
        $this->joinAuctionLotItem();
        $this->filterArray('CONCAT(ali.lot_num_prefix,ali.lot_num,ali.lot_num_ext)', $lotNL);
        return $this;
    }
}

