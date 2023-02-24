<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\AuctionEvent\Notify\Sms\FirstLotUpcoming;

use Auction;
use Sam\AuctionLot\AuctionEvent\Notify\Sms\FirstLotUpcoming\Internal\Load\DataProviderCreateTrait;
use Sam\AuctionLot\AuctionEvent\Notify\Sms\Template\AuctionLotSmsTemplateRendererCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Sms\ActionQueue\SmsActionQueueCreateTrait;
use Sam\Sms\ActionQueue\SmsActionQueueData;

/**
 * Send Lot Upcoming Notification for first lots in auction. Count depends on auction.notify_x_lots
 *
 * Class FirstLotUpcomingSmsNotifier
 * @package Sam\AuctionLot\Notify\Sms
 */
class FirstLotUpcomingSmsNotifier extends CustomizableClass
{
    use AuctionLotSmsTemplateRendererCreateTrait;
    use DataProviderCreateTrait;
    use SettingsManagerAwareTrait;
    use SmsActionQueueCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function notify(Auction $auction, int $editorUserId): void
    {
        $auctionLots = $this->createDataProvider()->loadFirstLots($auction->Id, $auction->NotifyXLots);
        if (!$auctionLots) {
            log_error('Available first auction lots not found' . composeSuffix(['a' => $auction->Id]));
            return;
        }

        foreach ($auctionLots as $auctionLot) {
            $lotItem = $this->createDataProvider()->loadLotItem($auctionLot->LotItemId);
            if (!$lotItem) {
                log_error("Available LotItem not found" . composeSuffix(['li' => $auctionLot->LotItemId]));
                continue;
            }
            $message = $this->createAuctionLotSmsTemplateRenderer()->render($lotItem, $auctionLot, $auction);
            $this->createSmsActionQueue()->add(
                SmsActionQueueData::new()->construct($auction->AccountId, $message),
                $editorUserId,
                $auction->Id
            );
        }
    }

    public function isEnabled(Auction $auction): bool
    {
        $isTextMsgEnabled = (bool)self::new()->getSettingsManager()->get(Constants\Setting::TEXT_MSG_ENABLED, $auction->AccountId);

        return $isTextMsgEnabled
            && $auction->NotifyXLots > 0
            && $auction->EventId !== '';
    }
}
