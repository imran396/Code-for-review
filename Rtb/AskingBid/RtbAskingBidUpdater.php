<?php
/**
 * Calculates running lot asking bid and updates rtb state
 *
 * SAM-5346: Rtb asking bid calculator
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           8/13/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\AskingBid;

use RtbCurrent;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Rtb\Increment\Calculate\RtbIncrementDetectorCreateTrait;

/**
 * Class CommandHelper
 * @package Sam\Rtb\Base
 */
class RtbAskingBidUpdater extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use RtbAskingBidDetectorCreateTrait;
    use RtbIncrementDetectorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * We update asking bid only when it is unset or dropped in running rtb session (rtbc.ask_bid)
     * @param RtbCurrent $rtbCurrent
     * @return bool
     */
    public function hasRunningAskingBid(RtbCurrent $rtbCurrent): bool
    {
        $shouldUpdate = Floating::gt($rtbCurrent->AskBid, 0);
        return $shouldUpdate;
    }

    /**
     * Calculate and update running lot asking bid (rtb_current.ask_bid). Don't persist.
     *
     * @param RtbCurrent $rtbCurrent
     * @param float|null $currentBid
     * @return RtbCurrent
     */
    public function update(RtbCurrent $rtbCurrent, ?float $currentBid): RtbCurrent
    {
        $lotItem = $this->getLotItemLoader()->load($rtbCurrent->LotItemId);
        if (!$lotItem) {
            log_debug(
                'Skip rtb asking bid detection, because running lot unset'
                . composeSuffix(['a' => $rtbCurrent->AuctionId])
            );
            return $rtbCurrent;
        }

        $auction = $this->getAuctionLoader()->load($rtbCurrent->AuctionId);
        if (!$auction) {
            log_error(
                "Available auction not found for rtbd updating asking bid"
                . composeSuffix(['a' => $rtbCurrent->AuctionId])
            );
            return $rtbCurrent;
        }

        $increment = $this->createRtbIncrementDetector()->detect($rtbCurrent, $currentBid);

        $isManualIncrement = $this->createRtbIncrementDetector()->hasManualIncrement($rtbCurrent);

        $detector = $this->createRtbAskingBidDetector()
            ->enableManualIncrement($isManualIncrement)
            ->enableSuggestedStartingBid($auction->SuggestedStartingBid)
            ->setAuctionId($rtbCurrent->AuctionId)
            ->setClerkingStyle($auction->ClerkingStyle)
            ->setCurrentBid($currentBid)
            ->setIncrement($increment)
            ->setLotItemId($rtbCurrent->LotItemId)
            ->setStartingBid($lotItem->StartingBid);
        $rtbCurrent->AskBid = $detector->detect();

        $this->log($rtbCurrent);
        return $rtbCurrent;
    }

    /**
     * @param RtbCurrent $rtbCurrent
     */
    protected function log(RtbCurrent $rtbCurrent): void
    {
        if (
            array_key_exists('AskBid', $rtbCurrent->__Modified)
            && Floating::neq($rtbCurrent->AskBid, $rtbCurrent->__Modified['AskBid'])
        ) {
            log_trace(
                "Running asking bid update"
                . composeSuffix(
                    [
                        'new' => $rtbCurrent->AskBid,
                        'old' => $rtbCurrent->__Modified['AskBid'],
                    ]
                )
            );
        }
    }
}
