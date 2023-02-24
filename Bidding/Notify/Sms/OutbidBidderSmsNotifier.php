<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\Notify\Sms;

use Auction;
use AuctionLotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidding\Notify\Sms\Internal\TemplateRendererCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Sms\ActionQueue\SmsActionQueueCreateTrait;
use Sam\Sms\ActionQueue\SmsActionQueueData;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Validate\UserExistenceCheckerAwareTrait;
use User;

/**
 * Class OutbidBidderSmsNotifier
 * @package Sam\Bidding\Notify
 */
class OutbidBidderSmsNotifier extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use SettingsManagerAwareTrait;
    use SmsActionQueueCreateTrait;
    use TemplateRendererCreateTrait;
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
     * Render outbid SMS template and add a message to the queue for sending in background
     *
     * @param User $user
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     */
    public function notify(User $user, AuctionLotItem $auctionLot, int $editorUserId): void
    {
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
        if (!$auction) {
            $message = "Available auction not found" . composeSuffix(['a' => $auctionLot->AuctionId]);
            log_error($message);
            return;
        }
        $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId);
        if (!$lotItem) {
            log_error("Available LotItem not found" . composeSuffix(['li' => $auctionLot->LotItemId]));
            return;
        }
        log_debug("Sending sms text outbid notification to user" . composeSuffix(['u' => $user->Email]));

        $message = $this->createTemplateRenderer()->render($lotItem, $auctionLot, $auction, $user);
        $this->createSmsActionQueue()->add(
            SmsActionQueueData::new()->construct($auction->AccountId, $message),
            $editorUserId
        );
    }

    /**
     * Check if notification is enabled
     *
     * @param User $user
     * @param AuctionLotItem $auctionLot
     * @return bool
     */
    public function isEnabled(User $user, AuctionLotItem $auctionLot): bool
    {
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
        if (!$auction) {
            $message = "Available auction not found" . composeSuffix(['a' => $auctionLot->AuctionId]);
            log_error($message);
            return false;
        }

        return $auction->EventId !== ''
            && $this->isTextMessageEnabled($auction)
            && $this->isAbleToSendSmsMessageToOutbidUser($user);
    }


    protected function isAbleToSendSmsMessageToOutbidUser(User $user): bool
    {
        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($user->Id);
        $isAble = $user->isActive()
            && $userInfo->isMobilePhoneType()
            && $userInfo->SendTextAlerts // send text is enabled
            && $userInfo->Phone !== '' // has mobile number
            && !$this->getUserExistenceChecker()->existByPhone($userInfo->Phone, [$userInfo->UserId]); // has no duplicate phone number
        return $isAble;
    }

    protected function isTextMessageEnabled(Auction $auction): bool
    {
        $isTextMsgEnabled = $this->getSettingsManager()->get(Constants\Setting::TEXT_MSG_ENABLED, $auction->AccountId);
        return $isTextMsgEnabled;
    }
}
