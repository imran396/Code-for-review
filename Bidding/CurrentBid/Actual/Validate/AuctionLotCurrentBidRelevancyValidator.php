<?php
/**
 * SAM-5620: Refactoring and unit tests for Actual Current Bid Detector module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 12, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\CurrentBid\Actual\Validate;

use AuctionLotItem;
use BidTransaction;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Check that the bid transaction is not failed or deleted and made by an active, not blocked user.
 *
 * Class AuctionLotCurrentBidRelevancyValidator
 * @package Sam\Bidding\CurrentBid\Actual\Validate
 */
class AuctionLotCurrentBidRelevancyValidator extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;
    use UserLoaderAwareTrait;

    public const ERR_CURRENT_BID_NOT_EXIST = 1;
    public const ERR_CURRENT_BID_DELETED = 2;
    public const ERR_CURRENT_BID_FAILED = 3;

    public const ERR_CURRENT_BID_USER_NOT_EXIST = 4;
    public const ERR_CURRENT_BID_USER_INVALID = 5;

    protected const ERROR_MESSAGES = [
        self::ERR_CURRENT_BID_NOT_EXIST => 'BidTransaction not found for current bid',
        self::ERR_CURRENT_BID_DELETED => 'BidTransaction is deleted',
        self::ERR_CURRENT_BID_FAILED => 'BidTransaction is failed',
        self::ERR_CURRENT_BID_USER_NOT_EXIST => 'Active user not found',
        self::ERR_CURRENT_BID_USER_INVALID => 'User is marked %s'
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int[]
     * @internal
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @return string[]
     * @internal
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @param BidTransaction|null $currentBid
     * @return bool
     * @internal
     */
    public function validate(AuctionLotItem $auctionLot, BidTransaction $currentBid = null): bool
    {
        $this->initResultStatusCollector();
        $userId = $currentBid->UserId ?? null;
        return $this->validateCurrentBid($currentBid, $auctionLot->CurrentBidId)
            && $this->validateCurrentHighBidder($userId);
    }

    protected function initResultStatusCollector(): void
    {
        $this->getResultStatusCollector()->construct(static::ERROR_MESSAGES);
    }

    /**
     * @param BidTransaction|null $currentBid
     * @param int $currentBidId
     * @return bool
     */
    private function validateCurrentBid(?BidTransaction $currentBid, int $currentBidId): bool
    {
        $logData = ['bt' => $currentBidId];
        if ($currentBid === null) {
            $this->processError(static::ERR_CURRENT_BID_NOT_EXIST, $logData);
            return false;
        }
        if ($currentBid->Deleted) {
            $this->processError(static::ERR_CURRENT_BID_DELETED, $logData);
            return false;
        }
        if ($currentBid->Failed) {
            $this->processError(static::ERR_CURRENT_BID_FAILED, $logData);
            return false;
        }
        return true;
    }

    /**
     * @param int|null $userId
     * @return bool
     */
    private function validateCurrentHighBidder(?int $userId): bool
    {
        $user = $this->getUserLoader()->load($userId);
        if (!$user) {
            $this->processError(static::ERR_CURRENT_BID_USER_NOT_EXIST, ['u' => $userId]);
            return false;
        }

        // We don't care about flag of bid user at the moment of lot closing and invoicing (SAM-6751)
        // TB: bids that have already been placed should be considered as winning (since the user entered a binding contract when placing bids)

//        $userFlag = $this->getUserFlagging()->getFlagByUser($user, $accountId);
//        if (in_array($userFlag, [Constants\User::FLAG_NOAUCTIONAPPROVAL, Constants\User::FLAG_BLOCK], true)) {
//            $flagName = $this->getUserRenderer()->renderFlag($userFlag);
//            $this->processError(static::ERR_CURRENT_BID_USER_INVALID, ['u' => $userId, 'acc' => $accountId], [$flagName]);
//            return false;
//        }

        return true;
    }

    /**
     * @param int $errorCode
     * @param array|null $logData
     * @param array|null $messageArgs
     * @return static
     */
    private function processError(int $errorCode, array $logData = null, array $messageArgs = null): static
    {
        $collector = $this->getResultStatusCollector();
        $message = $collector->getErrorMessageByCodeAmongAll($errorCode);
        if ($messageArgs) {
            $message = sprintf($message, ...$messageArgs);
        }
        if ($logData) {
            $message .= composeSuffix($logData);
        }
        $collector->addError($errorCode, $message);
        return $this;
    }
}
