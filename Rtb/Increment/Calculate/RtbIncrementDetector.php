<?php
/**
 * Detects running increment in live play
 *
 * SAM-5400: Rtb state update refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/18/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Increment\Calculate;

use Auction;
use RtbCurrent;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidding\BidIncrement\Load\BidIncrementLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Rtb\Increment\Load\AdvancedClerkingIncrementLoaderCreateTrait;

/**
 * Class RtbIncrementDetector
 * @package Sam\Rtb\Increment\Calculate
 */
class RtbIncrementDetector extends CustomizableClass
{
    use AdvancedClerkingIncrementLoaderCreateTrait;
    use AuctionLoaderAwareTrait;
    use BidIncrementLoaderAwareTrait;
    use LotItemLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Checks, if custom manual increment defined in rtb state. It considers decrement case too.
     * @param RtbCurrent $rtbCurrent
     * @return bool
     */
    public function hasManualIncrement(RtbCurrent $rtbCurrent): bool
    {
        /**
         * We should check on != 0, because manual increment may be negative number in case of Decrement enabled
         * It is positive number in general. "0" means default increment should be found.
         */
        return !Floating::eq($rtbCurrent->Increment, 0);
    }

    /**
     * Checks, if manual increment is intended for decrement
     * @param RtbCurrent $rtbCurrent
     * @return bool
     */
    public function hasManualDecrement(RtbCurrent $rtbCurrent): bool
    {
        return Floating::lt($rtbCurrent->Increment, 0);
    }

    /**
     * Return running increment. It is
     * either a) manual increment of rtb state,
     * or b) increment for respective clerking style and increment table
     * or c) zero value 0, when increment cannot be found
     * @param RtbCurrent $rtbCurrent
     * @param float|null $currentBid
     * @return float
     */
    public function detect(RtbCurrent $rtbCurrent, ?float $currentBid): float
    {
        $auctionId = $rtbCurrent->AuctionId;
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            log_error(
                "Available auction not found for increment detection"
                . composeSuffix(['a' => $auctionId])
            );
            return 0.;
        }

        if (!$rtbCurrent->LotItemId) {
            log_debug(
                'Skip rtbd increment detection, because running lot unset'
                . composeSuffix(['a' => $auctionId])
            );
            return 0.;
        }

        if ($this->hasManualIncrement($rtbCurrent)) {
            $increment = $rtbCurrent->Increment;
            log_trace(
                "Running increment found by manual increment from rtbd state"
                . composeSuffix(
                    [
                        'increment' => $increment,
                        'li' => $rtbCurrent->LotItemId,
                        'a' => $auctionId,
                    ]
                )
            );
        } else {
            if ($auction->isSimpleClerking()) {
                $increment = $this->findIncrementForSimpleClerking($rtbCurrent, $auction, $currentBid);
            } elseif ($auction->isAdvancedClerking()) {
                $increment = $this->findIncrementForAdvancedClerking($rtbCurrent);
            } else {
                log_error(
                    "Running increment not found, because of unknown auction clerking style"
                    . composeSuffix(['a' => $auctionId])
                );
                $increment = 0.;
            }
        }
        return $increment;
    }

    /**
     * Find increment value for Simple clerking according to increment table and current bid.
     * Don't consider manual increment stored in rtbd state.
     * @param RtbCurrent $rtbCurrent
     * @param Auction $auction
     * @param float|null $currentBid
     * @return float
     */
    protected function findIncrementForSimpleClerking(RtbCurrent $rtbCurrent, Auction $auction, ?float $currentBid): float
    {
        $hasBid = Floating::gt($currentBid, 0);
        if ($hasBid) {
            $bidAmount = $currentBid;
        } else {
            $lotItem = $this->getLotItemLoader()->load($rtbCurrent->LotItemId);
            if (!$lotItem) {
                log_error(
                    "Available lot item not found for detecting rtbd running increment"
                    . composeSuffix(['a' => $rtbCurrent->AuctionId, 'li' => $rtbCurrent->LotItemId])
                );
                return 0.;
            }
            $bidAmount = $lotItem->StartingBid;
        }
        $bidIncrement = $this->getBidIncrementLoader()->loadAvailable(
            $bidAmount,
            $auction->AccountId,
            $auction->AuctionType,
            $auction->Id,
            $rtbCurrent->LotItemId
        );
        $increment = $bidIncrement->Increment ?? 0.;
        log_trace(
            "Running increment found by " . ($hasBid ? 'current' : 'starting') . " bid and Simple clerking increment table"
            . composeSuffix(['increment' => $increment, 'bid' => $bidAmount])
        );
        return $increment;
    }

    /**
     * Find increment value for Advanced clerking according to default increment stored in rtbd state or advanced increment table.
     * Don't consider manual increment stored in rtbd state.
     * @param RtbCurrent $rtbCurrent
     * @return float
     */
    protected function findIncrementForAdvancedClerking(RtbCurrent $rtbCurrent): float
    {
        if (Floating::gt($rtbCurrent->DefaultIncrement, 0)) {
            $increment = $rtbCurrent->DefaultIncrement;
            log_trace(
                "Running increment found by default increment (Advanced clerking)"
                . composeSuffix(['increment' => $increment])
            );
        } else {
            $rtbCurrentIncrement = $this->createAdvancedClerkingIncrementLoader()
                ->loadFirstForAuction($rtbCurrent->AuctionId);
            if ($rtbCurrentIncrement) {
                $increment = $rtbCurrentIncrement->Increment;
                log_trace(
                    "Running increment found by advanced clerking increment table"
                    . composeSuffix(['increment' => $increment])
                );
            } else {
                $increment = Constants\Increment::ADVANCED_CLERKING_INCREMENT_DEFAULT;
                log_trace(
                    "Running increment found by constant default, because of empty Advanced clerking increment table"
                    . composeSuffix(['increment' => $increment])
                );
            }
        }
        return $increment;
    }
}
