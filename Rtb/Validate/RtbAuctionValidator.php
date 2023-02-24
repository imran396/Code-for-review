<?php
/**
 * Perform auction state and its lots validations, required for correct rtb play
 *
 * SAM-6438: Refactor auction state validation on rtb console load
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Rtb\Validate\Internal\Load\DataProviderCreateTrait;

/**
 * Class RtbAuctionValidator
 * @package Sam\Rtb
 */
class RtbAuctionValidator extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;
    use DataProviderCreateTrait;

    public const ERR_AUCTION_ABSENT = 1;
    public const ERR_BID_INCREMENT_NOT_FOUND = 2;
    public const ERR_ACTIVE_LOT_ABSENT = 3;
    public const ERR_ISSUES_WITH_LOTS = 4;
    public const ERR_AUCTION_INCREMENTS_MISS_ZERO_STARTING_RANGE = 5;
    public const ERR_ACCOUNT_INCREMENTS_MISS_ZERO_STARTING_RANGE = 6;

    public const DUPLICATED_LOT_NO_TPL = "Duplicate lot #%s found on item %s (item #%s)";
    public const EMPTY_LOT_NO_TPL = "Item %s (item #%s) has no lot#";

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Perform auction and its lots validations required for correct rtb play
     * @param int $auctionId
     * @return bool
     */
    public function validate(int $auctionId): bool
    {
        $this->initResultStatusCollector();
        $collector = $this->getResultStatusCollector();
        $dataProvider = $this->createDataProvider();

        $auction = $dataProvider->loadAuction($auctionId, true);
        if (!$auction) {
            $collector->addError(self::ERR_AUCTION_ABSENT);
            return false;
        }

        if ($auction->isSimpleClerking()) {
            if ($dataProvider->existAuctionBidIncrements($auction->Id, true)) {
                $isFoundBidIncrement = $dataProvider->existAuctionBidIncrementForZeroAmount($auction->Id, true);
                if (!$isFoundBidIncrement) {
                    $collector->addError(self::ERR_AUCTION_INCREMENTS_MISS_ZERO_STARTING_RANGE);
                    return false;
                }
            } elseif (!$dataProvider->existAccountBidIncrementForZeroAmount($auction->AccountId, $auction->AuctionType, true)) {
                $collector->addError(self::ERR_ACCOUNT_INCREMENTS_MISS_ZERO_STARTING_RANGE);
                return false;
            }
        }

        $auctionCache = $dataProvider->loadAuctionCache($auctionId, true);
        if (!$auctionCache || !$auctionCache->TotalActiveLots) {
            $collector->addError(self::ERR_ACTIVE_LOT_ABSENT);
            return false;
        }

        $errorMessages = [];
        $duplicatedInfos = $dataProvider->loadDuplicatedLotNoInfos($auctionId, true);
        foreach ($duplicatedInfos as $info) {
            $errorMessages[] = sprintf(self::DUPLICATED_LOT_NO_TPL, $info['lot_no'], $info['lot_name'], $info['item_no']);
        }
        $emptyInfos = $dataProvider->loadEmptyLotNoInfos($auctionId, true);
        foreach ($emptyInfos as $info) {
            $errorMessages[] = sprintf(self::EMPTY_LOT_NO_TPL, $info['lot_name'], $info['item_no']);
        }

        if ($errorMessages) {
            $collector->addError(self::ERR_ISSUES_WITH_LOTS, null, ['messages' => $errorMessages]);
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @return string[]
     */
    public function errorMessages(): array
    {
        $collector = $this->getResultStatusCollector();
        if ($collector->hasConcreteError(self::ERR_ISSUES_WITH_LOTS)) {
            $errorResultStatuses = $collector->findErrorResultStatusesByCodes([self::ERR_ISSUES_WITH_LOTS]);
            $errorResultStatus = current($errorResultStatuses);
            $messages = $errorResultStatus->getPayload()['messages'];
            return $messages;
        }
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * Error message html rendering method for admin side consoles (Live/Hybrid Clerk, Auctioneer)
     * @return string
     */
    public function buildErrorMessageHtmlForAdmin(): string
    {
        $messages = $this->errorMessages();
        if (!$messages) {
            return '';
        }
        $errorMessage = '<div class="error" style="font-weight:bold;padding:5px 0;">'
            . count($messages) . ' Errors found. <br /><br />';
        foreach ($messages as $error) {
            $errorMessage .= addslashes($error) . '<br />';
        }
        $errorMessage .= '<br /></div>';
        return $errorMessage;
    }

    protected function initResultStatusCollector(): void
    {
        $errorMessages = [
            self::ERR_AUCTION_ABSENT => 'Available auction not found',
            self::ERR_ACTIVE_LOT_ABSENT => 'Active lots for sell not found',
            self::ERR_ISSUES_WITH_LOTS => 'Auction lots have issues',
            self::ERR_AUCTION_INCREMENTS_MISS_ZERO_STARTING_RANGE => 'Auction bid increments miss zero starting range',
            self::ERR_ACCOUNT_INCREMENTS_MISS_ZERO_STARTING_RANGE => 'Account bid increments miss zero starting range',
        ];
        $this->getResultStatusCollector()->construct($errorMessages);
    }
}
