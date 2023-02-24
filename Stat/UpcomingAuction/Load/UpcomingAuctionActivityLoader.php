<?php
/**
 * SAM-7949: Predictive upcoming auction stats script
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Stat\UpcomingAuction\Load;

use QMySqli5DatabaseResult;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Stat\UpcomingAuction\Load\Dto\UpcomingAuctionActivityDto;

/**
 * Class UpcomingAuctionActivityLoader
 * @package Sam\Stat\UpcomingAuction\Load
 */
class UpcomingAuctionActivityLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use UpcomingAuctionActivityQueryBuilderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $days
     * @return UpcomingAuctionActivityDto[]
     */
    public function load(int $days): array
    {
        $query = $this->createUpcomingAuctionActivityQueryBuilder()
            ->construct()
            ->buildQuery($days);
        $dbResults = $this->multiQuery($query);
        $dbResult = current($dbResults);
        $activities = [];
        if ($dbResult) {
            while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
                $activities[] = UpcomingAuctionActivityDto::new()->fromDbRow($row);
            }
        }
        return $activities;
    }
}
