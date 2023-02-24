<?php
/**
 * SAM-10431: Refactor rtb catalog renderer for v3-7
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Catalog\Clerk\Render\Base\Internal\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class DataProvider
 * @package Sam\Rtb\Catalog\Clerk\Render\Base\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load admin catalog data
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadAdminCatalogData(int $auctionId, bool $isReadOnlyDb = false): array
    {
        $select = [
            'CONCAT(cbui.first_name, \' \', cbui.last_name) AS current_bidder_full_name',
            'CONCAT(sbui.first_name, \' \', sbui.last_name) AS second_bidder_full_name',
            'a.account_id',
            'a.auction_type',
            'a.test_auction',
            'ali.auction_id',
            'ali.buy_now',
            'ali.group_id',
            'ali.lot_item_id',
            'ali.lot_num',
            'ali.lot_num_ext',
            'ali.lot_num_prefix',
            'ali.lot_status_id',
            'ali.order',
            'alic.current_bidder_id',
            'alic.current_max_bid',
            'alic.second_bidder_id',
            'alic.second_max_bid',
            'alic.seo_url AS lot_seo_url',
            'cab.bidder_num as current_bidder_num',
            'cbu.username AS current_bidder_username',
            'curr.sign',
            'li.hammer_price',
            'li.high_estimate',
            'li.id',
            'li.low_estimate',
            'li.name',
            'li.winning_bidder_id',
            'sab.bidder_num as second_bidder_num',
            'sbu.username AS second_bidder_username',
        ];
        return $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAuction()
            ->joinAuctionLotItemCache()
            ->joinCurrency()
            ->joinCurrentAuctionBidder()
            ->joinCurrentBidderUser()
            ->joinCurrentBidderUserInfo()
            ->joinLotItemFilterActive(true)
            ->joinSecondAuctionBidder()
            ->joinSecondBidderUser()
            ->joinSecondBidderUserInfo()
            ->select($select)
            ->orderByOrder()
            ->orderByLotNumPrefix()
            ->orderByLotNum()
            ->orderByLotNumExt()
            ->loadRows();
    }
}
