<?php
/**
 * Updating auction info of lots, that is cached in lot_item.auction_info
 *
 * SAM-2745: Mossgreen - add auction# column to inventory screen
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           15 Oct, 2015
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\AuctionInfo;

use LotItem;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;

/**
 * Class Updater
 * @package Sam\Item\AuctionInfo
 */
class Updater extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use LotItemReadRepositoryCreateTrait;
    use LotItemWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Update sale# in lot_item.auction_info (SAM-2745)
     * @param LotItem $lotItem
     * @param int $editorUserId
     */
    public function refresh(LotItem $lotItem, int $editorUserId): void
    {
        $rows = $this->loadAuctionInfo($lotItem->Id);
        $auctionInfoSerialized = serialize($rows);
        $lotItem->AuctionInfo = $auctionInfoSerialized;
        $this->getLotItemWriteRepository()->saveWithModifier($lotItem, $editorUserId);
    }

    /**
     * Load id, sale#, account id for assigned auctions
     * @param int $lotItemId
     * @return array
     */
    public function loadAuctionInfo(int $lotItemId): array
    {
        $rows = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb(true)
            ->filterLotItemId($lotItemId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->select(['a.id', 'a.sale_num', 'a.sale_num_ext', 'a.account_id'])
            ->loadRows();
        return $rows;
    }

    /**
     * Update sale# in lot_item.auction_info for all lots in auction (SAM-2745)
     * @param int $auctionId
     * @param int $editorUserId
     */
    public function refreshForLotsInAuction(int $auctionId, int $editorUserId): void
    {
        $repo = $this->createLotItemReadRepository()
            ->enableReadOnlyDb(true)
            ->filterActive(true)
            ->joinAuctionLotItemFilterAuctionId($auctionId)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->setChunkSize(500);
        while ($lotItems = $repo->loadEntities()) {
            foreach ($lotItems as $lotItem) {
                $this->refresh($lotItem, $editorUserId);
            }
        }
    }
}
