<?php
/**
 * Updates manual increment in rtb state, if it wasn't defined before
 *
 * SAM-5346: Rtb asking bid calculator
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

namespace Sam\Rtb\Increment\Save;

use RtbCurrent;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Log\Support\SupportLoggerAwareTrait;
use Sam\Rtb\Increment\Calculate\RtbIncrementDetectorCreateTrait;
use Sam\Rtb\Increment\Load\AdvancedClerkingIncrementLoaderCreateTrait;

/**
 * Class RtbIncrementUpdater
 * @package Sam\Rtb\Increment\Save
 */
class RtbIncrementUpdater extends CustomizableClass
{
    use AdvancedClerkingIncrementLoaderCreateTrait;
    use AuctionLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;
    use RtbIncrementDetectorCreateTrait;
    use SupportLoggerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Update increment related data in running rtb state. Don't persist.
     * Make decision, if we should update or not inside
     * @param RtbCurrent $rtbCurrent
     * @param float|null $currentBid
     * @return RtbCurrent
     */
    public function update(RtbCurrent $rtbCurrent, ?float $currentBid): RtbCurrent
    {
        if ($this->createRtbIncrementDetector()->hasManualDecrement($rtbCurrent)) {
            /**
             * Manual decrement action affects rtb state's asking bid one time per click,
             * it doesn't continue to decrease asking bid after new bid placed.
             * System rotates negative increment value to positive,
             * so manual decrement is transformed to manual increment value now.
             */
            $rtbCurrent->Increment = abs($rtbCurrent->Increment);
        } elseif (!$this->createRtbIncrementDetector()->hasManualIncrement($rtbCurrent)) {
            /**
             * We update rtb state's increment only if, it wasn't manually re-defined by clerk
             */
            $auction = $this->getAuctionLoader()->load($rtbCurrent->AuctionId);
            if (!$auction) {
                log_error(
                    "Available auction not found for rtbd increment update"
                    . composeSuffix(['a' => $rtbCurrent->AuctionId])
                );
                return $rtbCurrent;
            }

            if (Floating::gt($currentBid, 0.)) { // Has current bid
                if ($auction->isAdvancedClerking()) {
                    $rtbCurrent->Increment = $rtbCurrent->DefaultIncrement;
                }
            } else { // Has no current bid
                if ($auction->isAdvancedClerking()) {
                    if (Floating::eq($rtbCurrent->DefaultIncrement, 0.)) {
                        $rtbCurrentIncrement = $this->createAdvancedClerkingIncrementLoader()
                            ->loadFirstForAuction($rtbCurrent->AuctionId);
                        if ($rtbCurrentIncrement) {
                            $rtbCurrent->DefaultIncrement = $rtbCurrentIncrement->Increment;
                        } else {
                            $rtbCurrent->DefaultIncrement = Constants\Increment::ADVANCED_CLERKING_INCREMENT_DEFAULT;
                        }
                    }
                    $rtbCurrent->Increment = $rtbCurrent->DefaultIncrement;
                } else { // Simple clerking
                    $rtbCurrent->Increment = null;
                }
            }
        }

        $this->log($rtbCurrent);
        return $rtbCurrent;
    }

    /**
     * @param RtbCurrent $rtbCurrent
     */
    protected function log(RtbCurrent $rtbCurrent): void
    {
        $logLevel = Constants\Debug::TRACE;
        $logger = $this->getSupportLogger();
        if (!$logger->isLogLevelEnough($logLevel)) {
            return;
        }

        $logData = [];
        if (
            array_key_exists('Increment', $rtbCurrent->__Modified)
            && Floating::neq($rtbCurrent->Increment, $rtbCurrent->__Modified['Increment'])
        ) {
            $logData = [
                'new' => $rtbCurrent->Increment,
                'old' => $rtbCurrent->__Modified['Increment'],
            ];
        }
        if (
            array_key_exists('DefaultIncrement', $rtbCurrent->__Modified)
            && Floating::neq($rtbCurrent->DefaultIncrement, $rtbCurrent->__Modified['DefaultIncrement'])
        ) {
            $logData = [
                'DefaultIncrement (new)' => $rtbCurrent->DefaultIncrement,
                'DefaultIncrement (old)' => $rtbCurrent->__Modified['DefaultIncrement'],
            ];
        }
        if ($logData) {
            $logger->log($logLevel, "Running increment update" . composeSuffix($logData));
        }
    }
}
