<?php
/**
 * SAM-5632: Rtb data provider for lot position
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 03, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Response\Concrete;

use AuctionLotItem;
use RtbCurrent;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Order\Query\AuctionLotOrderMysqlQueryBuilderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * This class is responsible for detecting position of lot in an auction
 *
 * Class LotPositionDataProducer
 * @package Sam\Rtb\Command\Response\Concrete
 */
class LotPositionDataProducer extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionLotItemReadRepositoryCreateTrait;
    use AuctionLotOrderMysqlQueryBuilderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return the position of an lot in an auction
     * @param RtbCurrent $rtbCurrent
     * @return array = [
     *  Constants\Rtb::RES_LOT_POSITION => int,
     * ]
     */
    public function produceData(RtbCurrent $rtbCurrent): array
    {
        $auctionLot = $this->getAuctionLotLoader()->load($rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        return $this->composeData($auctionLot);
    }

    /**
     * Return the position of an lot in an auction
     * @param AuctionLotItem|null $auctionLot
     * @return array
     */
    protected function composeData(?AuctionLotItem $auctionLot): array
    {
        $lotPosition = null;
        if ($auctionLot) {
            $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId, true);
            $lotStatuses = [
                Constants\Lot::LS_ACTIVE,
                Constants\Lot::LS_SOLD,
                Constants\Lot::LS_RECEIVED,
            ];
            if (!$auction->HideUnsoldLots) {
                $lotStatuses[] = Constants\Lot::LS_UNSOLD;
            }
            $prevLotsQuantity = $this->loadPrevLotsQty($auctionLot, $lotStatuses);
            $lotPosition = $prevLotsQuantity + 1;
        }
        $data[Constants\Rtb::RES_LOT_POSITION] = $lotPosition;
        return $data;
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @param array $lotStatuses
     * @return int
     */
    protected function loadPrevLotsQty(AuctionLotItem $auctionLot, array $lotStatuses): int
    {
        $prevLotCondition = $this->createAuctionLotOrderMysqlQueryBuilder()->buildPrevLotsWhereClause();
        $count = $this->createAuctionLotItemReadRepository()
            ->filterAuctionId($auctionLot->AuctionId)
            ->filterLotStatusId($lotStatuses)
            ->inlineCondition($prevLotCondition)
            ->joinAuctionLotItemFilterLotItemId($auctionLot->LotItemId)
            ->joinLotItemFilterActive(true)
            ->count();
        return $count;
    }
}
