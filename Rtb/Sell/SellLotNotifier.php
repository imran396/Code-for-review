<?php
/**
 * SAM-5495: Rtb server and daemon refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           11/18/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Sell;

use AuctionLotItem;
use Sam\Core\Service\CustomizableClass;
use Email_Template;
use LotItem;
use User;
use Sam\Core\Constants;

/**
 * Class SellLotNotifier
 * @package
 */
class SellLotNotifier extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param User $user
     * @param LotItem $lotItem
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     */
    public function sendWinningBidderNotification(
        User $user,
        LotItem $lotItem,
        AuctionLotItem $auctionLot,
        int $editorUserId
    ): void {
        $emailManager = Email_Template::new()->construct(
            $lotItem->AccountId,
            Constants\EmailKey::ITEM_WON_LIVE,
            $editorUserId,
            [$user, $lotItem, $auctionLot],
            $auctionLot->AuctionId
        );
        $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);

        $emailManager = Email_Template::new()->construct(
            $lotItem->AccountId,
            Constants\EmailKey::WINNING_BID_NOTIFICATION_SENT_CONSIGNOR,
            $editorUserId,
            [$user, $lotItem, $auctionLot],
            $auctionLot->AuctionId
        );
        $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);
    }
}
