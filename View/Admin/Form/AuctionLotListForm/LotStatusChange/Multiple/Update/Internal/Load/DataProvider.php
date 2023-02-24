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

namespace Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Update\Internal\Load;

use AuctionLotItem;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class DataProvider
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotStatusChange\Multiple\Update\Internal\Load
 */
class DataProvider extends CustomizableClass
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
     * @param array $lotItemIds
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItem[]
     */
    public function loadAuctionLotEntities(array $lotItemIds, int $auctionId, bool $isReadOnlyDb = false): array
    {
        $auctionLots = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotItemId($lotItemIds)
            ->filterAuctionId($auctionId)
            ->loadEntities();
        return $auctionLots;
    }
}
