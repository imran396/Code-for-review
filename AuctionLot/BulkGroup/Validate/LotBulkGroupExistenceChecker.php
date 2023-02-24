<?php
/**
 * SAM-5408 : Apply LotBulkGroupExistenceChecker
 * https://bidpath.atlassian.net/browse/SAM-5408
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 21, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\BulkGroup\Validate;

use Sam\AuctionLot\LotNo\Parse\LotNoParserCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class LotBulkGroupExistenceChecker
 * @package Sam\AuctionLot\BulkGroup
 */
class LotBulkGroupExistenceChecker extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use LotNoParserCreateTrait;

    /**
     * Class instantiation method
     *
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check bulk master exists or not
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existBulkMasterLotInAuction(?int $auctionId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->createAuctionLotItemReadRepository()
            ->joinLotItemFilterActive(true)
            ->filterAuctionId($auctionId)
            ->filterByMasterRole()
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->enableReadOnlyDb($isReadOnlyDb)
            ->exist();
        return $isFound;
    }

    /**
     * @param string $lotNoConcatenated
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existMasterAuctionLotByLotNoConcatenated(string $lotNoConcatenated, int $auctionId, bool $isReadOnlyDb = false): bool
    {
        if ($lotNoConcatenated === Constants\LotBulkGroup::LBGR_MASTER) {
            return true;
        }

        $lotNoParser = $this->createLotNoParser()->construct();
        if (!$lotNoParser->validate($lotNoConcatenated)) {
            return false;
        }
        $lotNoParsed = $lotNoParser->parse($lotNoConcatenated);
        $isFound = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterByMasterRole()
            ->filterLotNum($lotNoParsed->lotNum)
            ->filterLotNumExt($lotNoParsed->lotNumExtension)
            ->filterLotNumPrefix($lotNoParsed->lotNumPrefix)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->exist();
        return $isFound;
    }

//    /**
//     * @param int $auctionId
//     * @param int $itemNumber
//     * @param string $itemNumberExt
//     * @param bool $isReadOnlyDb
//     * @return bool
//     */
//    public function existItemBulkMasterByItemNo($auctionId, $itemNumber, string $itemNumberExt = '', bool $isReadOnlyDb = false): bool
//    {
//        $isFound = $this->createAuctionLotItemReadRepository()
//            ->filterByMasterRole()
//            ->filterAuctionId($auctionId)
//            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
//            ->joinLotItemFilterActive(true)
//            ->joinLotItemFilterItemNum($itemNumber)
//            ->joinLotItemFilterItemNumExt($itemNumberExt)
//            ->enableReadOnlyDb($isReadOnlyDb)
//            ->exist();
//        return $isFound;
//    }

    /**
     * @param int $masterAuctionLotId
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function countPiecemealLots(int $masterAuctionLotId, bool $isReadOnlyDb = false): int
    {
        $count = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterByPiecemealRole($masterAuctionLotId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->count();
        return $count;
    }

    /**
     * Checks, if lot bulk grouping exists that is referenced by respective master auction lot.
     * Note, that we consider such grouping exists, only when at least one piecemeal lot is added to the group.
     * @param int $masterAuctionLotId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existPiecemealGrouping(int $masterAuctionLotId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterByPiecemealRole($masterAuctionLotId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->exist();
        return $isFound;
    }
}
