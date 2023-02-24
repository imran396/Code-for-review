<?php
/**
 * Send email/sms notifications to high/outbid bidders, consignor.
 * These methods are called individually comparing with AbsenteeBidNotifier.
 *
 * SAM-4163: Extract timed bid sms/email notification
 *
 * @author        Igors Kotlevskis
 * @since         Mar 23, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidding\TimedBid\Notify;

use Auction;
use AuctionLotItem;
use BidTransaction;
use Email_Template;
use LotItem;
use Sam\Bidding\Notify\Sms\OutbidBidderSmsNotifierCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Email\Load\EmailTemplateLoaderAwareTrait;
use User;

/**
 * Class TimedBidNotifier
 * @package Sam\Bidding\TimedBid
 */
class TimedBidNotifier extends CustomizableClass
{
    use EmailTemplateLoaderAwareTrait;
    use OutbidBidderSmsNotifierCreateTrait;

    protected int $priority = Constants\ActionQueue::MEDIUM;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Send general notification email about placed bid to consignor (Email_Template::TimedBidConsignorNotification)
     * @param LotItem $lotItem
     * @param Auction $auction
     * @param BidTransaction $bidTransaction
     * @param int $editorUserId
     */
    public function notifyConsignorByEmail(
        LotItem $lotItem,
        Auction $auction,
        BidTransaction $bidTransaction,
        int $editorUserId
    ): void {
        if (!$lotItem->hasConsignor()) {
            return;
        }

        $emailManager = null;
        $emailKey = Constants\EmailKey::TIMED_BID_CONSIGNOR_NOTIFICATION;
        $auctionEmailTemplate = $this->getEmailTemplateLoader()->loadByKeyAucId(
            $bidTransaction->AuctionId,
            $emailKey
        );

        if (
            $auctionEmailTemplate
            && $auctionEmailTemplate->NotifyConsignor
        ) {
            $emailManager = Email_Template::new()->construct(
                $auction->AccountId,
                $emailKey,
                $editorUserId,
                [$bidTransaction],
                $bidTransaction->AuctionId
            );
        } else {
            $emailTemplate = $this->getEmailTemplateLoader()->loadByKey($emailKey, $auction->AccountId);
            if (!$emailTemplate) {
                log_error(
                    "Available email template not found by key and account"
                    . composeSuffix(['key' => $emailKey, 'acc' => $auction->AccountId])
                );
                return;
            }
            if ($emailTemplate->NotifyConsignor) {
                $emailManager = Email_Template::new()->construct(
                    $auction->AccountId,
                    $emailKey,
                    $editorUserId,
                    [$bidTransaction]
                );
            }
        }

        if ($emailManager) {
            $emailManager->addToActionQueue($this->priority);
            $this->log("Timed bid general notification email sent to consignor (Placed bid: %s)", $bidTransaction);
        }
    }

    /**
     * Send high bid notification email to consignor (Email_Template::WinnerBidConsignorNotification)
     * @param LotItem $lotItem
     * @param Auction $auction
     * @param BidTransaction $winnerBid
     * @param User $winnerUser
     * @param float $placedBidAmount
     * @param int $editorUserId
     */
    public function notifyConsignorWinnerBidByEmail(
        LotItem $lotItem,
        Auction $auction,
        BidTransaction $winnerBid,
        User $winnerUser,
        float $placedBidAmount,
        int $editorUserId
    ): void {
        if ($lotItem->hasConsignor()) {
            $emailManager = null;
            $emailTemplate = $this->getEmailTemplateLoader()->loadByKeyAucId($auction->Id, Constants\EmailKey::WINNER_BID_CONSIGNOR_NOTIFICATION);
            if (
                $emailTemplate
                && $emailTemplate->NotifyConsignor
            ) {
                $emailManager = Email_Template::new()->construct(
                    $auction->AccountId,
                    Constants\EmailKey::WINNER_BID_CONSIGNOR_NOTIFICATION,
                    $editorUserId,
                    [$winnerUser, $winnerBid, $placedBidAmount, $lotItem],
                    $auction->Id
                );
            } else {
                $emailTemplate = $this->getEmailTemplateLoader()->loadByKey(Constants\EmailKey::WINNER_BID_CONSIGNOR_NOTIFICATION, $auction->AccountId);
                if ($emailTemplate->NotifyConsignor) {
                    $emailManager = Email_Template::new()->construct(
                        $auction->AccountId,
                        Constants\EmailKey::WINNER_BID_CONSIGNOR_NOTIFICATION,
                        $editorUserId,
                        [$winnerUser, $winnerBid, $placedBidAmount, $lotItem]
                    );
                }
            }
            if ($emailManager) {
                $emailManager->addToActionQueue($this->priority);
                $this->log("Timed high bid notification email sent to consignor (High bid: %s)", $winnerBid);
            }
        }
    }

    /**
     * Send high bid notification to high bidder (Email_Template::WinnerBid)
     * @param LotItem $lotItem
     * @param Auction $auction
     * @param BidTransaction $winnerBid
     * @param User $winnerUser
     * @param float $placedBidAmount
     * @param int $editorUserId
     */
    public function notifyWinnerByEmail(
        LotItem $lotItem,
        Auction $auction,
        BidTransaction $winnerBid,
        User $winnerUser,
        float $placedBidAmount,
        int $editorUserId
    ): void {
        $templateKey = $auction->Reverse ? Constants\EmailKey::WINNER_BID_REVERSE : Constants\EmailKey::WINNER_BID;
        $emailManager = Email_Template::new()->construct(
            $auction->AccountId,
            $templateKey,
            $editorUserId,
            [$winnerUser, $winnerBid, $placedBidAmount, $lotItem],
            $auction->Id
        );
        $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);
        $this->log(
            "Timed high bid confirmation email sent to high bidder "
            . "(email: {$winnerUser->Email}, high bid: %s)",
            $winnerBid
        );
    }

    /**
     * Send outbid notification email to outbid bidder (Email_Template::Outbid)
     * @param LotItem $lotItem
     * @param Auction $auction
     * @param BidTransaction $winnerBid
     * @param User $outbidUser
     * @param float $outbidAmount
     * @param int $editorUserId
     */
    public function notifyOutbidBidderByEmail(
        LotItem $lotItem,
        Auction $auction,
        BidTransaction $winnerBid,
        User $outbidUser,
        float $outbidAmount,
        int $editorUserId
    ): void {
        $emailManager = Email_Template::new()->construct(
            $auction->AccountId,
            Constants\EmailKey::OUTBID,
            $editorUserId,
            [$outbidUser, $outbidAmount, $winnerBid, $lotItem],
            $auction->Id
        );
        $emailManager->addToActionQueue();
        $this->log(
            "Timed outbid notification email sent to outbid bidder" . composeSuffix(
                [
                    'email' => $outbidUser->Email,
                    'u' => $outbidUser->Id,
                    'outbid amount' => $outbidAmount,
                ]
            )
        );
    }

    /**
     * Send outbid notification sms to outbid bidder
     * @param User $outbidUser
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     */
    public function notifyOutbidBidderBySms(User $outbidUser, AuctionLotItem $auctionLot, int $editorUserId): void
    {
        $smsNotifier = $this->createOutbidBidderSmsNotifier();
        if ($smsNotifier->isEnabled($outbidUser, $auctionLot)) {
            $smsNotifier->notify($outbidUser, $auctionLot, $editorUserId);
            log_debug(
                "Timed outbid notification sms sent to outbid bidder"
                . composeSuffix(['email' => $outbidUser->Email, 'u' => $outbidUser->Id])
            );
        }
    }

    /**
     * @param string $message
     * @param BidTransaction|null $bid null when bid absent
     */
    protected function log(string $message, BidTransaction $bid = null): void
    {
        if ($bid) {
            $message = sprintf(
                $message,
                composeSuffix(
                    [
                        'u' => $bid->UserId,
                        'li' => $bid->LotItemId,
                        'a' => $bid->AuctionId,
                        'max bid' => $bid->MaxBid,
                    ]
                )
            );
        }
        log_debug($message);
    }

    /**
     * @param int $priority
     * @return static
     */
    public function setPriority(int $priority): static
    {
        $this->priority = $priority;
        return $this;
    }
}
