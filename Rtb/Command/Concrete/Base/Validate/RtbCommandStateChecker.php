<?php
/**
 * SAM-10837: Race condition issue of rtb commands "Change Increment", "Place Floor Bid" sequence - Add console state synchronization check by the current bid value
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\Base\Validate;

use RtbCurrent;
use Sam\Bidding\CurrentBid\HighBidDetectorCreateTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;

/**
 * Class CommandStateChecker
 * @package Sam\Rtb\Command\Concrete\Base\Validate
 */
class RtbCommandStateChecker extends CustomizableClass
{
    use HighBidDetectorCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check the "Current Bid" value correspondence between console and server states.
     * @param RtbCurrent $rtbCurrent
     * @param float|null $clientCurrentBid
     * @return bool
     */
    public function checkCurrentBidAmountSync(RtbCurrent $rtbCurrent, ?float $clientCurrentBid): bool
    {
        $serverCurrentBid = $this->createHighBidDetector()->detectAmount($rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $success = Floating::eq($clientCurrentBid, $serverCurrentBid);
        if (!$success) {
            $logData = [
                'client current bid' => $clientCurrentBid,
                'server current bid' => $serverCurrentBid,
                'li' => $rtbCurrent->LotItemId,
                'a' => $rtbCurrent->AuctionId,
            ];
            log_warning(
                "Out of Sync by Current Bid: Client side current bid value does not match server value"
                . composeSuffix($logData)
            );
        }
        return $success;
    }

}
