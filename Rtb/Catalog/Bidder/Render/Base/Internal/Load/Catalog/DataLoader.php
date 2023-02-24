<?php
/**
 * Data load
 *
 * SAM-10431: Refactor rtb catalog renderer for v3-7
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Jul 09, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Catalog\Bidder\Render\Base\Internal\Load\Catalog;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class DataProvider
 */
class DataLoader extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load public catalog data
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadPublicCatalogData(
        int $auctionId,
        bool $isReadOnlyDb = false
    ): array {
        return $this->prepareRepository($auctionId, $isReadOnlyDb)
            ->considerOptionHideUnsoldLots()
            ->loadRows();
    }

    /**
     * Load public catalog data
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadPublicCatalogLotData(
        int $lotItemId,
        int $auctionId,
        bool $isReadOnlyDb = false
    ): array {
        return $this->prepareRepository($auctionId, $isReadOnlyDb)
            ->considerOptionHideUnsoldLots()
            ->filterLotItemId($lotItemId)
            ->loadRow();
    }

    /**
     * Prepare repository
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItemReadRepository
     */
    protected function prepareRepository(
        int $auctionId,
        bool $isReadOnlyDb = false
    ): AuctionLotItemReadRepository {
        $select = [
            'a.account_id',
            'a.auction_type',
            'a.test_auction',
            'ali.auction_id',
            'ali.buy_now',
            'ali.lot_item_id',
            'ali.lot_num',
            'ali.lot_num_ext',
            'ali.lot_num_prefix',
            'ali.lot_status_id',
            'ali.order',
            'alic.seo_url AS lot_seo_url',
            'curr.sign',
            'li.hammer_price',
            'li.high_estimate',
            'li.id',
            'li.low_estimate',
            'li.name',
        ];
        $repo = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAuction()
            ->joinAuctionLotItemCache()
            ->joinCurrency()
            ->joinLotItemFilterActive(true)
            ->orderByOrder()
            ->orderByLotNumPrefix()
            ->orderByLotNum()
            ->orderByLotNumExt()
            ->select($select);
        return $repo;
    }
}
