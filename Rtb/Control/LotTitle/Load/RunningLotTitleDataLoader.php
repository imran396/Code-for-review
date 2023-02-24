<?php
/**
 * SAM-6435: Refactor data loader for every rtb console running lot title
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Control\LotTitle\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class RunningLotTitleDataLoader
 * @package Sam\Rtb
 */
class RunningLotTitleDataLoader extends CustomizableClass
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
     * Get the highest lot number of items on its auction
     * @param int $auctionId auction id
     * @param bool $isReadOnlyDb
     * @return int highest lot number
     */
    public function detectHighestLotNum(int $auctionId, bool $isReadOnlyDb = false): int
    {
        $row = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->joinLotItemFilterActive(true)
            ->orderByLotNum(false)
            ->select(['MAX(ali.lot_num) AS lot_num'])
            ->loadRow();
        $highNum = (int)$row['lot_num'];
        return $highNum;
    }
}
