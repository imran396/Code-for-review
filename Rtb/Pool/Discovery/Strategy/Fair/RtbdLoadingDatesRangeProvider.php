<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 06, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Discovery\Strategy\Fair;

use Sam\Core\Service\CustomizableClass;
use DateTime;
use Sam\Rtb\Pool\Discovery\Strategy\Fair\Load\RtbdLoadingDatesRangeDecisionDataLoaderCreateTrait;
use Sam\Rtb\Pool\Instance\RtbdDescriptor;

/**
 * Class RtbdLoadingDatesRangeProvider
 * @package Sam\Rtb\Pool\Discovery\Strategy\Fair
 */
class RtbdLoadingDatesRangeProvider extends CustomizableClass
{
    use RtbdLoadingDatesRangeDecisionDataLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array|RtbdDescriptor[] $rtbdList
     * @return array
     * @throws \Exception
     */
    public function getLoadingDatesRange(array $rtbdList): array
    {
        $datesRangeDecisionData = $this->createRtbdLoadingDatesRangeDecisionDataLoader()->load($rtbdList);

        $rtbdLoadingDatesRange = [];
        foreach ($datesRangeDecisionData as $decisionDatum) {
            if ($decisionDatum['start_closing_date'] && $decisionDatum['end_date']) {
                $rtbdName = $decisionDatum['rtbd_name'];
                $rtbdLoadingDatesRange[$rtbdName][] = [
                    new DateTime($decisionDatum['start_closing_date']),
                    new DateTime($decisionDatum['end_date'])
                ];
            }
        }

        return $rtbdLoadingDatesRange;
    }
}
