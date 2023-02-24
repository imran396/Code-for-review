<?php
/**
 * Helper methods for AuctionBidder manipulations
 *
 * SAM-3893: Refactor auction bidder related functionality
 * SAM-3904: Auction bidder registration class
 *
 * @author        Igors Kotlevskis
 * @since         Nov 30, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidder\AuctionBidder;

use AuctionBidder;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Save\AuctionBidderDbLockerCreateTrait;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderCheckerAwareTrait;
use Sam\Bidder\BidderNum\Advise\BidderNumberAdviserAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Billing\AuctionRegistration\AuthAmountDetectorAwareTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionBidder\AuctionBidderWriteRepositoryAwareTrait;
use Sam\User\Account\Save\UserAccountProducerAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Validate\UserExistenceCheckerAwareTrait;

/**
 * Class Helper
 * @package Sam\Bidder\AuctionBidder
 */
class Helper extends CustomizableClass
{
    use AuctionBidderCheckerAwareTrait;
    use AuctionBidderDbLockerCreateTrait;
    use AuctionBidderLoaderAwareTrait;
    use AuctionBidderNotifierAwareTrait;
    use AuctionBidderWriteRepositoryAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuthAmountDetectorAwareTrait;
    use BidderNumPaddingAwareTrait;
    use BidderNumberAdviserAwareTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use UserAccountProducerAwareTrait;
    use UserExistenceCheckerAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $userId
     * @param int $auctionId
     * @return AuctionBidder
     */
    public function create(int $userId, int $auctionId): AuctionBidder
    {
        $auctionBidder = $this->createEntityFactory()->auctionBidder();
        $auctionBidder->AuctionId = $auctionId;
        $auctionBidder->UserId = $userId;
        $auctionBidder->RegisteredOn = $this->getCurrentDateUtc();
        $auctionBidder->CarrierMethod = '';
        return $auctionBidder;
    }

    /**
     * @param AuctionBidder $auctionBidder
     * @param string|null $bidderNumPad Null if not set
     * @return AuctionBidder
     */
    public function approve(AuctionBidder $auctionBidder, ?string $bidderNumPad): AuctionBidder
    {
        $auctionBidder->BidderNum = $bidderNumPad;
        return $auctionBidder;
    }

    /**
     * Check if AuctionBidder is approved
     * @param AuctionBidder|null $auctionBidder
     * @return bool
     */
    public function isApproved(?AuctionBidder $auctionBidder = null): bool
    {
        $isApproved = $auctionBidder
            && $this->isApprovedByBidderNum($auctionBidder->BidderNum);
        return $isApproved;
    }

    /**
     * Check if bidder is approved in auction, when we know aub.bidder_num
     * @param string|null $bidderNum Null if not set
     * @return bool
     */
    public function isApprovedByBidderNum(?string $bidderNum): bool
    {
        return $this->getBidderNumberPadding()->isFilled($bidderNum);
    }

    /**
     * @param AuctionBidder $auctionBidder
     * @return AuctionBidder
     */
    public function disapprove(AuctionBidder $auctionBidder): AuctionBidder
    {
        $auctionBidder->BidderNum = null;
        return $auctionBidder;
    }

    /**
     * @param AuctionBidder $auctionBidder
     * @param float|null $authAmount
     * @return AuctionBidder
     */
    public function addAuthInfo(AuctionBidder $auctionBidder, ?float $authAmount): AuctionBidder
    {
        $auctionBidder->AuthAmount = $authAmount;
        $auctionBidder->AuthDate = $this->getCurrentDateUtc();
        return $auctionBidder;
    }

    /**
     * @param AuctionBidder $auctionBidder
     * @return AuctionBidder
     */
    public function dropAuthInfo(AuctionBidder $auctionBidder): AuctionBidder
    {
        $auctionBidder->AuthAmount = null;
        $auctionBidder->AuthDate = null;
        return $auctionBidder;
    }

    /**
     * @param AuctionBidder $auctionBidder
     * @return string|null
     */
    public function suggestBidderNum(AuctionBidder $auctionBidder): ?string
    {
        $user = $this->getUserLoader()->load($auctionBidder->UserId);
        if (!$user) {
            log_error(
                "Available user not found, when suggesting bidder#"
                . composeSuffix(['u' => $auctionBidder->UserId])
            );
            return null;
        }
        if ($user->UsePermanentBidderno) {
            $isFound = $this->getAuctionBidderChecker()
                ->existBidderNo($user->CustomerNo, $auctionBidder->AuctionId, [$user->Id]);
            if ($isFound) {
                $bidderNum = $this->getBidderNumberAdviser()
                    ->construct()
                    ->suggest($auctionBidder->AuctionId);
            } else {
                $bidderNum = $this->getBidderNumberPadding()->add($user->CustomerNo);
            }
        } else {
            $bidderNum = $this->getBidderNumberAdviser()
                ->construct()
                ->suggest($auctionBidder->AuctionId);
        }
        return $bidderNum;
    }

    /**
     * Register bidder in auction, assign bidder#, approve it and send email
     * Bidder# assigning rule:
     * - $bidderNum, if it is passed;
     * - keep the same bidder#, if bidder already registered in auction;
     * - next available bidder#
     * @param int $userId
     * @param int $auctionId
     * @param string $bidderNum
     * @param int $editorUserId
     * @return AuctionBidder|null null in case of auction not found error
     */
    public function produceApprovedBidder(int $userId, int $auctionId, string $bidderNum, int $editorUserId): ?AuctionBidder
    {
        $this->createAuctionBidderDbLocker()->lock($userId, $auctionId);
        $auctionBidder = $this->getAuctionBidderLoader()
            ->enableEntityMemoryCacheManager(false)
            ->load($userId, $auctionId);
        $isBidderAlreadyRegisteredInAuction = $auctionBidder instanceof AuctionBidder;
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            log_error(
                "Available auction not found, when producing approved bidder"
                . composeSuffix(['a' => $auctionId])
            );
            return null;
        }

        if (!$auctionBidder) {
            $auctionBidder = $this->create($userId, $auctionId);
            $this->getAuctionBidderWriteRepository()->saveWithModifier($auctionBidder, $editorUserId);
            $this->getUserAccountProducer()->add($userId, $auction->AccountId, $editorUserId);
        }
        $this->createAuctionBidderDbLocker()->release($userId, $auctionId);

        if ($bidderNum !== '') {
            $bidderNum = $this->getBidderNumberPadding()->add($bidderNum);
        } elseif (
            $isBidderAlreadyRegisteredInAuction
            && $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum) !== ''
        ) {
            $bidderNum = $auctionBidder->BidderNum;
        } else {
            $bidderNum = $this->getBidderNumberAdviser()
                ->construct()
                ->suggest($auctionBidder->AuctionId);
        }

        $auctionBidder = $this->approve($auctionBidder, $bidderNum);

        $this->getAuctionBidderNotifier()->notifyAuctionApproved($auctionBidder, $editorUserId);

        return $auctionBidder;
    }
}
