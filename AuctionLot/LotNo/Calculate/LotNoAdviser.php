<?php
/**
 * SAM-5651: Refactor Lot No auto filling service
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 02, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\LotNo\Calculate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class LotNoAdviser
 * @package Sam\AuctionLot\LotNo\Calculate
 */
class LotNoAdviser extends CustomizableClass
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
     * Returns the next available auction lot item number
     *
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function suggest(int $auctionId, bool $isReadOnlyDb = false): int
    {
        $row = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->select(['MAX(lot_num) AS next_num'])
            ->loadRow();
        $lotNum = isset($row['next_num']) ? (int)$row['next_num'] + 1 : 1;
        return $lotNum;
    }
}
