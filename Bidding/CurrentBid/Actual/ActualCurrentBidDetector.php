<?php
/**
 * SAM-4025: Actual bid detection issue
 *
 * @author        Igors Kotlevskis
 * @since         Jan 1, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 * Notes:
 * It checks, if bid transaction not deleted, not failed, exists and if active user exists and he isn't marked "Blocked" or "Non auction approval".
 * We don't correct AuctionLotItem->CurrentBidId anywhere in closer. Seems, it shouldn't affect any function after lot closing (eg. invoice generation).
 * Although, we still see such bids in transaction history.
 */

namespace Sam\Bidding\CurrentBid\Actual;

use AuctionLotItem;
use BidTransaction;
use Sam\Bidding\CurrentBid\Actual\Load\ActualCurrentBidLoaderCreateTrait;
use Sam\Bidding\CurrentBid\Actual\Validate\AuctionLotCurrentBidRelevancyValidatorCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ActualCurrentBidDetector
 * @package Sam\Bidding\CurrentBid\Actual
 */
class ActualCurrentBidDetector extends CustomizableClass
{
    use ActualCurrentBidLoaderCreateTrait;
    use AuctionLotCurrentBidRelevancyValidatorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @return BidTransaction|null
     */
    public function findForTimed(AuctionLotItem $auctionLot): ?BidTransaction
    {
        $actualBidTransaction = null;
        if ($auctionLot->CurrentBidId) {
            $loader = $this->createActualCurrentBidLoader();
            $actualBidTransaction = $loader->loadCurrentBid($auctionLot->CurrentBidId);
            $validator = $this->createAuctionLotCurrentBidRelevancyValidator();

            if (!$validator->validate($auctionLot, $actualBidTransaction)) {
                $actualBidTransaction = $loader->loadActualBidForTimed($auctionLot);
                $message = implode("\n", $validator->errorMessages());
                $actualBidTransaction
                    ? $this->logNewActualBidDetected($actualBidTransaction, $message)
                    : $this->logActualBidNotFound($auctionLot, $message);
            }
        }
        return $actualBidTransaction;
    }

    /**
     * @param BidTransaction $actualBid
     * @param string $reason
     */
    private function logNewActualBidDetected(BidTransaction $actualBid, string $reason): void
    {
        log_info(
            sprintf(
                'New actual current bid detected %s AuctionLotItem->CurrentBidId rejected because of reason: %s',
                composeSuffix(['li' => $actualBid->LotItemId, 'a' => $actualBid->AuctionId, 'bt' => $actualBid->Id]),
                $reason
            )
        );
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @param string $reason
     */
    private function logActualBidNotFound(AuctionLotItem $auctionLot, string $reason): void
    {
        log_info(
            sprintf(
                'Actual current bid cannot be found for lot %s because of reason: %s',
                composeSuffix(['li' => $auctionLot->LotItemId, 'a' => $auctionLot->AuctionId]),
                $reason
            )
        );
    }
}
