<?php
/**
 * Register and call email notifications
 *
 * SAM-3904: Auction bidder registration class
 *
 * @author        Igors Kotlevskis
 * @since         Sep 14, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidder\AuctionBidder\Register\Notify;

use AuctionBidder;
use Sam\Bidder\AuctionBidder\AuctionBidderNotifierAwareTrait;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionBidderRegistrationNotifier
 * @package Sam\Bidder\AuctionBidder\Register
 */
class Notifier extends CustomizableClass
{
    use AuctionBidderNotifierAwareTrait;

    private const AUCTION_REGISTERED = 'AuctionRegistered';
    private const AUCTION_APPROVED = 'AuctionApproved';
    private const AUTH_FAILED = 'AuthFailed';
    private const AUTH_SUCCESS = 'AuthSuccess';

    /**
     * @var array
     */
    protected array $notifications = [];

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
     */
    public function addAuctionRegistered(AuctionBidder $auctionBidder): void
    {
        $this->addNotification(self::AUCTION_REGISTERED, $auctionBidder);
    }

    /**
     * @param AuctionBidder $auctionBidder
     */
    public function addAuctionApproved(AuctionBidder $auctionBidder): void
    {
        $this->addNotification(self::AUCTION_APPROVED, $auctionBidder);
    }

    /**
     * @param AuctionBidder $auctionBidder
     * @param array $args
     */
    public function addAuthSuccess(AuctionBidder $auctionBidder, array $args): void
    {
        $this->addNotification(self::AUTH_SUCCESS, $auctionBidder, $args);
    }

    /**
     * @param AuctionBidder $auctionBidder
     * @param array $args
     */
    public function addAuthFailed(AuctionBidder $auctionBidder, array $args): void
    {
        $this->addNotification(self::AUTH_FAILED, $auctionBidder, $args);
    }

    /**
     * Add notification callbacks, that should be called later
     * @param string $type
     * @param AuctionBidder $auctionBidder
     * @param array $args
     * @internal param string $methodName
     */
    protected function addNotification(string $type, AuctionBidder $auctionBidder, array $args = []): void
    {
        $this->notifications[$auctionBidder->Id][$type] = [$auctionBidder, $args];
    }

    /**
     * Run notification callbacks
     * @param int $auctionId
     * @param int $editorUserId
     */
    public function callNotifications(int $auctionId, int $editorUserId): void
    {
        foreach ($this->notifications as $arr) {
            if (
                array_key_exists(self::AUCTION_APPROVED, $arr)
                && array_key_exists(self::AUCTION_REGISTERED, $arr)
            ) {
                // Drop AuctionRegistered notification, if AuctionApproved notification is set
                unset($arr[self::AUCTION_REGISTERED]);
            }

            if (array_key_exists(self::AUTH_SUCCESS, $arr)) {
                $auctionBidder = $arr[self::AUTH_SUCCESS][0];
                $authAmount = Cast::toFloat($arr[self::AUTH_SUCCESS][1]['amount']);
                $this->getAuctionBidderNotifier()->notifyAuthSuccess($auctionBidder, $editorUserId, $authAmount);
            }

            if (array_key_exists(self::AUTH_FAILED, $arr)) {
                $auctionBidder = $arr[self::AUTH_FAILED][0];
                $authAmount = Cast::toFloat($arr[self::AUTH_FAILED][1]['amount']);
                $this->getAuctionBidderNotifier()->notifyAuthFailed($auctionBidder, $editorUserId, $authAmount);
            }

            if (array_key_exists(self::AUCTION_APPROVED, $arr)) {
                $auctionBidder = $arr[self::AUCTION_APPROVED][0];
                $generalAuctionId = $auctionId;
                $this->getAuctionBidderNotifier()->notifyAuctionApproved($auctionBidder, $editorUserId, $generalAuctionId);
            }

            if (array_key_exists(self::AUCTION_REGISTERED, $arr)) {
                $auctionBidder = $arr[self::AUCTION_REGISTERED][0];
                $generalAuctionId = $auctionId;
                $this->getAuctionBidderNotifier()->notifyAuctionRegistered($auctionBidder, $editorUserId, $generalAuctionId);
            }
        }
    }
}
