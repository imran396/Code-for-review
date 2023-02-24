<?php
/**
 * SAM-10180: Extract logic of date and time assignment to auction lots collection from the "Auction Lot List" page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 10, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Update\Internal\Load;

use AuctionLotItem;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;

/**
 * Class DataProvider
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotDateTimeApplying\Update\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use TimezoneLoaderAwareTrait;

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
    public function loadOrderedAuctionLots(array $lotItemIds, int $auctionId, bool $isReadOnlyDb = false): array
    {
        $auctionLots = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemIds)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->orderByOrder()
            ->orderByLotNumPrefix()
            ->orderByLotNum()
            ->orderByLotNumExt()
            ->loadEntities();
        return $auctionLots;
    }

    public function detectTimezoneIdByLocationOrCreatePersisted(string $location): int
    {
        return $this->getTimezoneLoader()->loadByLocationOrCreatePersisted($location)->Id;
    }

}
