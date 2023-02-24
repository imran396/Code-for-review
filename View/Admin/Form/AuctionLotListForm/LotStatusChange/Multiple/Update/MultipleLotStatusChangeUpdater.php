<?php
/**
 * SAM-10177: Decouple the "Lot status change" function at the "Auction Lot List" page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Update;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Update\Internal\Load\DataProviderCreateTrait;

/**
 * Class MultipleLotStatusChangeUpdater
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Update
 */
class MultipleLotStatusChangeUpdater extends CustomizableClass
{
    use AuctionLotItemWriteRepositoryAwareTrait;
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function updateStatus(int $targetLotStatus, array $lotItemIds, int $auctionId, int $editorUserId): void
    {
        $auctionLots = $this->createDataProvider()->loadAuctionLotEntities($lotItemIds, $auctionId);
        foreach ($auctionLots as $auctionLot) {
            $auctionLot->LotStatusId = $targetLotStatus;
            $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
        }
    }
}
