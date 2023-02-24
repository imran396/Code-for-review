<?php
/**
 * SAM-6481: Add bidding options on the admin - auction - lots - added lots table
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\PlaceBid\Internal\Command;

use Sam\Application\Controller\Admin\PlaceBid\PlaceBidCommand;
use Sam\AuctionLot\Load\AuctionLotCacheLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderCheckerAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Validator contains mandatory place bid command validation rules that are applicable for all auction types
 *
 * Class PlaceBidCommandValidator
 * @package Sam\Application\Controller\Admin\PlaceBid\Internal\Command
 * @internal
 */
class PlaceBidCommandValidator extends CustomizableClass
{
    use AuctionBidderCheckerAwareTrait;
    use AuctionLotCacheLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use ResultStatusCollectorAwareTrait;

    public const ERR_ACCESS_DENIED = 1;
    public const ERR_AVAILABLE_AUCTION_LOT_NOT_FOUND = 2;
    public const ERR_INCORRECT_MAX_BID = 3;
    public const ERR_USER_NOT_APPROVED = 4;
    public const ERR_USER_NOT_REGISTERED = 5;

    protected const ERROR_MESSAGES = [
        self::ERR_ACCESS_DENIED => 'Access denied',
        self::ERR_AVAILABLE_AUCTION_LOT_NOT_FOUND => 'Can\'t find auction lot by id',
        self::ERR_INCORRECT_MAX_BID => 'Incorrect max bid',
        self::ERR_USER_NOT_APPROVED => 'You are not approved for the auction',
        self::ERR_USER_NOT_REGISTERED => 'You are not registered for the auction',
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
     * @param PlaceBidCommand $command
     * @return bool
     */
    public function validate(PlaceBidCommand $command): bool
    {
        $collector = $this->getResultStatusCollector()->construct(static::ERROR_MESSAGES);

        $auctionLot = $this->getAuctionLotLoader()->load($command->lotItemId, $command->auctionId);
        if (!$auctionLot) {
            log_error(
                "Available auction lot not found" . composeSuffix(
                    [
                        'a' => $command->auctionId,
                        'alid' => $command->auctionLotId,
                        'lid' => $command->lotItemId
                    ]
                )
            );
            $collector->addError(self::ERR_AVAILABLE_AUCTION_LOT_NOT_FOUND);
            return false;
        }
        if (!$command->bidderId) {
            $collector->addError(self::ERR_ACCESS_DENIED);
            return false;
        }
        if (!$this->isAuctionRegistered($command->auctionId, $command->bidderId)) {
            $collector->addError(self::ERR_USER_NOT_REGISTERED);
            return false;
        }
        if (!$this->isAuctionApproved($command->auctionId, $command->bidderId)) {
            $collector->addError(self::ERR_USER_NOT_APPROVED);
            return false;
        }
        if (!$command->maxBidAmount) {
            $collector->addError(self::ERR_INCORRECT_MAX_BID);
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function errorMessage(): string
    {
        $errorMessages = $this->getResultStatusCollector()->getErrorMessages();
        return $errorMessages ? reset($errorMessages) : '';
    }

    /**
     * Check if bidder approved in auction (cached)
     * @param int|null $auctionId null/0 for unassigned to auction lots
     * @param int $userId
     * @return bool
     */
    protected function isAuctionApproved(?int $auctionId, int $userId): bool
    {
        return $this->getAuctionBidderChecker()->isAuctionApproved($userId, $auctionId);
    }

    /**
     * Check if user registered in auction (cached)
     * @param int|null $auctionId
     * @param int $userId
     * @return bool
     */
    protected function isAuctionRegistered(?int $auctionId, int $userId): bool
    {
        return $this->getAuctionBidderChecker()->isAuctionRegistered($userId, $auctionId);
    }
}
