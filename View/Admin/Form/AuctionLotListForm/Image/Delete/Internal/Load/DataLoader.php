<?php
/**
 * SAM-7911: Refactor \LotImage_Deleter
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\Image\Delete\Internal\Load;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class DataLoader
 * @package Sam\View\Admin\Form\AuctionLotListForm\Image\Delete\Internal\Load
 * @internal
 */
class DataLoader extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return array of lot item ids assigned to auction
     * @param int $auctionId
     * @return int[]
     */
    public function loadLotItemIdsInAuction(int $auctionId): array
    {
        $rows = $this->createAuctionLotItemReadRepository()
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->select(['ali.lot_item_id'])
            ->loadRows();
        $lotItemIds = ArrayCast::arrayColumnInt($rows, 'lot_item_id');
        return $lotItemIds;
    }
}
