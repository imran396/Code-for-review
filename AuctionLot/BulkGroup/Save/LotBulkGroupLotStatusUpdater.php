<?php
/**
 * SAM-5636: Refactoring of auction_closer.php - move piecemeal lot updating logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 06, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\BulkGroup\Save;

use AuctionLotItem;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;

/**
 * Class LotBulkGroupLotStatusUpdater
 * @package Sam\AuctionLot\BulkGroup
 */
class LotBulkGroupLotStatusUpdater extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Update bulk group lot(piecemeal) status according to bulk master lot status
     * @param AuctionLotItem $masterAuctionLot
     * @param int $editorUserId
     * @return void
     */
    public function updateByMasterAuctionLot(AuctionLotItem $masterAuctionLot, int $editorUserId): void
    {
        log_debug(
            'Updating bulk group of lot status' . composeSuffix(
                [
                    'Bulk master lot status' => $masterAuctionLot->LotStatusId,
                    'Bulk master lot' => $masterAuctionLot->Id,
                ]
            )
        );

        $auctionLots = $this->createAuctionLotItemReadRepository()
            ->filterByPiecemealRole($masterAuctionLot->Id)
            ->loadEntities();

        foreach ($auctionLots as $auctionLot) {
            $auctionLot->LotStatusId = $masterAuctionLot->LotStatusId;
            $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
        }
    }
}
