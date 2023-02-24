<?php
/**
 * Email notifications related to user registration and approval in auction, successful and failed CC authorization
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
use Email_Template;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class Notifier
 * @package Sam\Bidder\AuctionBidder
 */
class Notifier extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AuctionBidder $auctionBidder
     * @param int $editorUserId
     * @param int|null $generalAuctionId
     */
    public function notifyAuctionApproved(AuctionBidder $auctionBidder, int $editorUserId, ?int $generalAuctionId = null): void
    {
        $shouldSend = $this->shouldSend($auctionBidder, $generalAuctionId);
        if ($shouldSend) {
            $auction = $this->getAuctionLoader()->load($auctionBidder->AuctionId, true);
            if (!$auction) {
                log_error(
                    "Available auction not found, when email notifying bidder about auction approving"
                    . composeSuffix(['a' => $auctionBidder->AuctionId, 'u' => $auctionBidder->UserId])
                );
                return;
            }
            $emailManager = Email_Template::new()->construct(
                $auction->AccountId,
                Constants\EmailKey::AUCTION_APP,
                $editorUserId,
                [$auctionBidder],
                $auctionBidder->AuctionId
            );
            $emailManager->addToActionQueue(Constants\ActionQueue::LOW);
        }
    }

    /**
     * @param AuctionBidder $auctionBidder
     * @param int $editorUserId
     * @param int|null $generalAuctionId
     */
    public function notifyAuctionRegistered(AuctionBidder $auctionBidder, int $editorUserId, ?int $generalAuctionId = null): void
    {
        $shouldSend = $this->shouldSend($auctionBidder, $generalAuctionId);
        if ($shouldSend) {
            $auction = $this->getAuctionLoader()->load($auctionBidder->AuctionId, true);
            if (!$auction) {
                log_error(
                    "Available auction not found, when email notifying bidder about auction registration"
                    . composeSuffix(['a' => $auctionBidder->AuctionId, 'u' => $auctionBidder->UserId])
                );
                return;
            }
            $emailManager = Email_Template::new()->construct(
                $auction->AccountId,
                Constants\EmailKey::AUCTION_REG,
                $editorUserId,
                [$auctionBidder],
                $auctionBidder->AuctionId
            );
            $emailManager->addToActionQueue(Constants\ActionQueue::LOW);
        }
    }

    /**
     * @param AuctionBidder $auctionBidder
     * @param int $editorUserId
     * @param float|null $authAmount
     */
    public function notifyAuthFailed(AuctionBidder $auctionBidder, int $editorUserId, ?float $authAmount): void
    {
        $auction = $this->getAuctionLoader()->load($auctionBidder->AuctionId, true);
        if (!$auction) {
            log_error(
                "Available auction not found, when email notifying bidder about auction approving"
                . composeSuffix(['a' => $auctionBidder->AuctionId, 'u' => $auctionBidder->UserId])
            );
            return;
        }
        $emailManager = Email_Template::new()->construct(
            $auction->AccountId,
            Constants\EmailKey::AUTH_FAILED,
            $editorUserId,
            ['aucBidder' => $auctionBidder, 'amount' => $authAmount],
            $auctionBidder->AuctionId
        );
        $emailManager->addToActionQueue();
    }

    /**
     * @param AuctionBidder $auctionBidder
     * @param int $editorUserId
     * @param float|null $authAmount
     */
    public function notifyAuthSuccess(AuctionBidder $auctionBidder, int $editorUserId, ?float $authAmount): void
    {
        $auction = $this->getAuctionLoader()->load($auctionBidder->AuctionId, true);
        if (!$auction) {
            log_error(
                "Available auction not found, when email notifying bidder about auction approving"
                . composeSuffix(['a' => $auctionBidder->AuctionId, 'u' => $auctionBidder->UserId])
            );
            return;
        }
        $emailManager = Email_Template::new()->construct(
            $auction->AccountId,
            Constants\EmailKey::AUTH_SUCCESS,
            $editorUserId,
            ['aucBidder' => $auctionBidder, 'amount' => $authAmount],
            $auctionBidder->AuctionId
        );
        $emailManager->addToActionQueue(Constants\ActionQueue::LOW);
    }

    /**
     * @param AuctionBidder $auctionBidder
     * @param int|null $generalAuctionId null - absent auction leads to true result
     * @return bool
     */
    protected function shouldSend(AuctionBidder $auctionBidder, ?int $generalAuctionId = null): bool
    {
        $shouldSend = true;
        if ($generalAuctionId !== null) {
            $auction = $this->getAuctionLoader()->load($auctionBidder->AuctionId, true);
            if (!$auction) {
                log_error(
                    "Available auction not found, when email notifying bidder about auction approving"
                    . composeSuffix(['a' => $auctionBidder->AuctionId, 'u' => $auctionBidder->UserId])
                );
                return false;
            }
            $accountId = $auction->AccountId;
            $isOnlyOneRegEmail = $this->getSettingsManager()->get(Constants\Setting::ONLY_ONE_REG_EMAIL, $accountId);
            if (
                $isOnlyOneRegEmail
                && $auctionBidder->AuctionId !== $generalAuctionId
            ) {
                $shouldSend = false;
            }
        }
        return $shouldSend;
    }
}
