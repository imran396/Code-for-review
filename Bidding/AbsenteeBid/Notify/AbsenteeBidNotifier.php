<?php
/**
 * Send email/sms notifications to high/outbid bidders, consignor
 *
 * SAM-4161: Extract absentee bid sms/email notification
 *
 * @author        Igors Kotlevskis
 * @since         Mar 22, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidding\AbsenteeBid\Notify;

use AbsenteeBid;
use Email_Template;
use Sam\Auction\Validate\AuctionStatusCheckerAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Bidding\AbsenteeBid\Detect\HighAbsenteeBidDetectorCreateTrait;
use Sam\Bidding\Notify\Sms\OutbidBidderSmsNotifierCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Email\Load\EmailTemplateLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class AbsenteeBidNotifier
 * @package Sam\Bidding\AbsenteeBid
 */
class AbsenteeBidNotifier extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionStatusCheckerAwareTrait;
    use EmailTemplateLoaderAwareTrait;
    use HighAbsenteeBidDetectorCreateTrait;
    use LotItemAwareTrait;
    use OutbidBidderSmsNotifierCreateTrait;
    use UserLoaderAwareTrait;

    protected AbsenteeBid $currentAbsentee;
    protected ?AbsenteeBid $previousHighAbsentee = null;
    protected ?AbsenteeBid $highAbsentee = null;
    protected ?int $accountId = null;
    protected int $priority = Constants\ActionQueue::MEDIUM;
    protected int $editorUserId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        AbsenteeBid $currentAbsentee,
        ?AbsenteeBid $previousHighAbsentee,
        int $editorUserId,
        int $priority = Constants\ActionQueue::MEDIUM
    ): static {
        $this->currentAbsentee = $currentAbsentee;
        $this->previousHighAbsentee = $previousHighAbsentee;
        $this->editorUserId = $editorUserId;
        $this->priority = $priority;
        return $this;
    }

    /**
     * Send email/sms notifications to high/outbid bidders, consignor
     */
    public function notify(): void
    {
        if ($this->currentAbsentee->BidType === Constants\Bid::ABT_REGULAR) {
            $this->init();
            $this->notifyBidConfirmationByEmail();
            $this->notifyOutbidBiddersByEmail();
            $this->notifyOutbidBidderBySms();
            $this->notifyConsignorByEmail();
        }
    }

    /**
     * Initialize state
     */
    protected function init(): void
    {
        $this->setAuctionId($this->currentAbsentee->AuctionId);
        $this->setLotItemId($this->currentAbsentee->LotItemId);
        $this->accountId = $this->getAuction()->AccountId;
        $this->highAbsentee = $this->createHighAbsenteeBidDetector()
            ->detectFirstHigh($this->getLotItemId(), $this->getAuctionId());
    }

    /**
     * Send email notification regarding placed absentee bid (Email_Template::AbsenteeBid)
     */
    protected function notifyBidConfirmationByEmail(): void
    {
        $emailManager = Email_Template::new()->construct(
            $this->accountId,
            Constants\EmailKey::ABSENTEE_BID,
            $this->editorUserId,
            [$this->currentAbsentee],
            $this->currentAbsentee->AuctionId
        );
        $emailManager->addToActionQueue($this->priority);
        $bidStatus = $this->isCurrentHighBid() ? '(high bid)' : '(outbid)';
        $this->log("Absentee bid confirmation email sent to current bidder {$bidStatus}", $this->currentAbsentee);
    }

    /**
     * Send email to outbid bidder only (Email_Template::AbsenteeOutbid)
     */
    protected function notifyOutbidBiddersByEmail(): void
    {
        $auction = $this->getAuction();
        if (!$auction) {
            log_error("Available auction not found, when notify outbid bidders by email" . composeSuffix(['a' => $this->getAuctionId()]));
            return;
        }
        if (
            $this->getAuctionStatusChecker()->isAccessOutbidWinningInfo($auction)
            && $this->isPreviousHighOutbid()
        ) {
            $bid = $this->previousHighAbsentee;
            $emailManager = Email_Template::new()->construct(
                $this->accountId,
                Constants\EmailKey::ABSENTEE_OUTBID,
                $this->editorUserId,
                [$bid],
                $bid->AuctionId
            );
            $emailManager->addToActionQueue($this->priority);
            $this->log("Outbid email sent to previous high absentee bidder", $bid);
        }
    }

    /**
     * Send sms to outbid bidder
     */
    protected function notifyOutbidBidderBySms(): void
    {
        $auction = $this->getAuction();
        if (!$auction) {
            log_error("Available auction not found, when notify outbid bidders by sms" . composeSuffix(['a' => $this->getAuctionId()]));
            return;
        }
        if (
            $this->getAuctionStatusChecker()->isAccessOutbidWinningInfo($auction)
            && $this->isPreviousHighOutbid()
        ) {
            $bid = $this->previousHighAbsentee;
            $user = $this->getUserLoader()->load($bid->UserId);
            if (!$user) {
                log_error(
                    "Available user not found, when notifying outbid bidder by sms"
                    . composeSuffix(['u' => $bid->UserId, 'li' => $bid->LotItemId, 'a' => $bid->AuctionId])
                );
                return;
            }
            $auctionLot = $this->getAuctionLotLoader()->load($bid->LotItemId, $bid->AuctionId);
            if (!$auctionLot) {
                log_error(
                    "Available auction lot not found, when notifying outbid bidder by sms"
                    . composeSuffix(['a' => $bid->AuctionId, 'li' => $bid->LotItemId, 'u' => $bid->UserId])
                );
                return;
            }
            $smsNotifier = $this->createOutbidBidderSmsNotifier();
            if ($smsNotifier->isEnabled($user, $auctionLot)) {
                $smsNotifier->notify($user, $auctionLot, $this->editorUserId);
                $this->log("Outbid sms sent to previous high absentee bidder", $bid);
            }
        }
    }

    /**
     * Send email about placed bid to consignor (Email_Template::AbsenteeBidConsignorNotification)
     */
    protected function notifyConsignorByEmail(): void
    {
        $lotItem = $this->getLotItem();

        if (
            $lotItem
            && $lotItem->hasConsignor()
        ) {
            $absenteeBid = $this->currentAbsentee;
            $emailManager = null;
            $auctionEmailTemplate = $this->getEmailTemplateLoader()->loadByKeyAucId(
                $this->getAuctionId(),
                Constants\EmailKey::ABSENTEE_BID_CONSIGNOR_NOTIFICATION
            );
            if (
                $auctionEmailTemplate
                && $auctionEmailTemplate->NotifyConsignor
            ) {
                $emailManager = Email_Template::new()->construct(
                    $this->accountId,
                    Constants\EmailKey::ABSENTEE_BID_CONSIGNOR_NOTIFICATION,
                    $this->editorUserId,
                    [$absenteeBid],
                    $this->getAuctionId()
                );
            } else {
                $key = Constants\EmailKey::ABSENTEE_BID_CONSIGNOR_NOTIFICATION;
                $emailTemplate = $this->getEmailTemplateLoader()->loadByKey($key, $this->accountId);
                if (!$emailTemplate) {
                    log_error(
                        "Available email template not found by key and account"
                        . composeSuffix(['key' => $key, 'acc' => $this->accountId])
                    );
                    return;
                }
                if ($emailTemplate->NotifyConsignor) {
                    $emailManager = Email_Template::new()->construct(
                        $this->accountId,
                        Constants\EmailKey::ABSENTEE_BID_CONSIGNOR_NOTIFICATION,
                        $this->editorUserId,
                        [$absenteeBid]
                    );
                }
            }
            if ($emailManager) {
                $emailManager->addToActionQueue($this->priority);
                $this->log("Absentee bid notification email sent to consignor", $absenteeBid);
            }
        }
    }

    /**
     * Check, if current absentee bid outbid previous high absentee, placed by another bidder
     * @return bool
     */
    protected function isPreviousHighOutbid(): bool
    {
        $isPreviousHighOutbid = $this->previousHighAbsentee
            && $this->highAbsentee->Id !== $this->previousHighAbsentee->Id;
        return $isPreviousHighOutbid;
    }

    /**
     * Check, if current absentee bid is currently highest bid
     * @return bool
     */
    protected function isCurrentHighBid(): bool
    {
        $is = !$this->highAbsentee
            || $this->currentAbsentee->Id === $this->highAbsentee->Id;
        return $is;
    }

    /**
     * @param string $message
     * @param AbsenteeBid $bid
     */
    protected function log(string $message, AbsenteeBid $bid): void
    {
        $logData = [
            'u' => $bid->UserId,
            'li' => $bid->LotItemId,
            'a' => $bid->AuctionId,
            'max bid' => $bid->MaxBid
        ];
        log_debug($message . composeSuffix($logData));
    }
}
