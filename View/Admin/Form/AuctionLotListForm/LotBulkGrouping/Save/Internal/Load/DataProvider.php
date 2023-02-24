<?php
/**
 * SAM-6627: Extract "Add to Bulk" updating functionality from Admin Auction Lot List page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotBulkGrouping\Save\Internal\Load;

use AuctionLotItem;
use Sam\AuctionLot\BulkGroup\Load\LotBulkGroupLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataProvider
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotBulkGrouping\Save\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use AuctionLotLoaderAwareTrait;
    use LotBulkGroupLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $lotItemIds
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItem[]
     */
    public function loadTargetAuctionLotsByLotItemIds(array $lotItemIds, int $auctionId, bool $isReadOnlyDb = false): array
    {
        return $this->getAuctionLotLoader()->loadByLotItemIds($lotItemIds, $auctionId, $isReadOnlyDb);
    }

    /**
     * @param int $auctionLotId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItem|null
     */
    public function loadMasterAuctionLotById(int $auctionLotId, bool $isReadOnlyDb = false): ?AuctionLotItem
    {
        return $this->getAuctionLotLoader()->loadById($auctionLotId, $isReadOnlyDb);
    }

    /**
     * @param int[] $auctionLotIds
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadAuctionLotsByIds(array $auctionLotIds, bool $isReadOnlyDb = false): array
    {
        return $this->getAuctionLotLoader()->loadByIds($auctionLotIds, $isReadOnlyDb);
    }

    /**
     * @param string $lotNoConcatenated
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    public function detectMasterAuctionLotIdByLotNoConcatenated(
        string $lotNoConcatenated,
        int $auctionId,
        bool $isReadOnlyDb = false
    ): ?int {
        return $this->getLotBulkGroupLoader()
            ->detectMasterAuctionLotIdByLotNoConcatenated($lotNoConcatenated, $auctionId, $isReadOnlyDb);
    }
}
