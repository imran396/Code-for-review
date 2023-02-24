<?php
/**
 * SAM-11220: Implement unit tests for RegularBidPureChecker and OfferBidSaver
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 14, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\OfferBid\Place\Internal\Load;

use DateTime;
use Sam\Bidding\TimedOnlineOfferBid\Load\TimedOnlineOfferBidLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use TimedOnlineOfferBid;

/**
 * Class DataProvider
 * @package Sam\Invoice\StackedTax\Generate\Item\Single\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use DateHelperAwareTrait;
    use TimedOnlineOfferBidLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadTimedOnlineOfferBid($userId, $auctionLotId): ?TimedOnlineOfferBid
    {
        return $this->getTimedOnlineOfferBidLoader()->loadByUserAndAuctionLotAndCounterBid($userId, $auctionLotId);
    }

    public function detectCurrentDateUtc(): DateTime
    {
        return $this->getDateHelper()->detectCurrentDateUtc();
    }
}
