<?php
/**
 * SAM-5635: Seconds left and seconds before detector
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Янв. 01, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Date;

use DateTime;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Lot\Search\Query\Build\Helper\LotSearchQueryBuilderHelperCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * This class is responsible for calculating the remaining time before the start and end of the lot
 *
 * Class LotSecondLeftDetector
 * @package Sam\Lot\Date
 */
class LotSecondLeftDetector extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use DateHelperAwareTrait;
    use LotSearchQueryBuilderHelperCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get the remaining second before the lot start and seconds before it ends
     *
     * @param int $lotItemId timed_online_item.lot_item_id
     * @param int $auctionId timed_online_item.Auction_id
     * @param bool $isReadOnlyDb
     * @return int[] array(int seconds_before, int seconds_left)
     */
    public function detect(int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): array
    {
        [$startDate, $endDate] = $this->detectLotStartAndEndDate($lotItemId, $auctionId, $isReadOnlyDb);
        $currentDateTimestamp = $this->getDateHelper()->detectCurrentDateUtc()->getTimestamp();
        $secondsBefore = $startDate->getTimestamp() - $currentDateTimestamp;
        $secondsLeft = $endDate->getTimestamp() - $currentDateTimestamp;
        return [$secondsBefore, $secondsLeft];
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return array|DateTime[]
     */
    private function detectLotStartAndEndDate(int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): array
    {
        $mySearchQueryManager = $this->createLotSearchQueryBuilderHelper();
        $dates = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAuction()
            ->joinAuctionDynamic()
            ->joinAuctionLotItemCache()
            ->select(
                [
                    $mySearchQueryManager->getTimedLotStartDateExpr() . ' AS start',
                    $mySearchQueryManager->getTimedLotEndDateExpr() . ' AS end'
                ]
            )
            ->loadRow();
        return [new DateTime((string)$dates['start']), new DateTime((string)$dates['end'])];
    }
}
