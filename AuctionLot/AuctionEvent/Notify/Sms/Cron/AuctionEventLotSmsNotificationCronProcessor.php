<?php
/**
 * SAM-5638: Refactor SMS Text Message notification sender for the auction event upcoming lot items
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\AuctionEvent\Notify\Sms\Cron;

use AuctionLotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\AuctionEvent\Notify\Sms\Cron\Internal\Load\DataLoaderAwareTrait;
use Sam\AuctionLot\AuctionEvent\Notify\Sms\Template\AuctionLotSmsTemplateRendererCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Sms\ActionQueue\SmsActionQueueCreateTrait;
use Sam\Sms\ActionQueue\SmsActionQueueData;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Collects unnotified lots and add notification messages to the queue. Run by cron
 *
 * Class AuctionEventLotSmsNotificationCronProcessor
 * @package Sam\AuctionLot\AuctionEvent\Notify\Sms\Cron
 */
class AuctionEventLotSmsNotificationCronProcessor extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotSmsTemplateRendererCreateTrait;
    use CurrentDateTrait;
    use DataLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use SettingsManagerAwareTrait;
    use SmsActionQueueCreateTrait;
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
     * Run notification process
     */
    public function process(): void
    {
        log_info('cron sms text notifications started at ' . $this->getCurrentDateUtcIso());

        $auctionLots = $this->getDataLoader()->loadSmsUnnotifiedTimedLots();
        foreach ($auctionLots as $auctionLot) {
            if ($this->isSmsNotificationEnabled($auctionLot->AccountId)) {
                $this->notify($auctionLot);
                $this->markLotAsNotified($auctionLot);
            }
        }

        log_info('cron sms text notifications ended at ' . $this->getCurrentDateUtcIso());
    }

    protected function notify(AuctionLotItem $auctionLot): void
    {
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
        if (!$auction) {
            log_error(
                "Available auction not found, when processing in cron auction event sms notification for lot"
                . composeSuffix(['a' => $auctionLot->AuctionId])
            );
            return;
        }
        $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId);
        if (!$lotItem) {
            log_error(
                "Available LotItem not found, when processing in cron auction event sms notification for lot"
                . composeSuffix(['li' => $auctionLot->LotItemId, 'ali' => $auctionLot->Id])
            );
            return;
        }
        $message = $this->createAuctionLotSmsTemplateRenderer()->render($lotItem, $auctionLot, $auction);
        log_debug(
            'Saving SMS Text'
            . composeSuffix(['li' => $auctionLot->LotItemId, 'a' => $auctionLot->AuctionId])
        );
        $this->addNotificationToQueue($message, $auction->AccountId);
    }

    protected function markLotAsNotified(AuctionLotItem $auctionLot): void
    {
        $auctionLot->TextMsgNotified = true;
        $this->getAuctionLotItemWriteRepository()->saveWithSystemModifier($auctionLot);
    }

    /**
     * @param string $message
     * @param int $accountId
     */
    protected function addNotificationToQueue(string $message, int $accountId): void
    {
        $this->createSmsActionQueue()->add(
            SmsActionQueueData::new()->construct($accountId, $message),
            $this->getUserLoader()->loadSystemUserId()
        );
    }

    /**
     * @param int $accountId
     * @return bool
     */
    protected function isSmsNotificationEnabled(int $accountId): bool
    {
        return (bool)$this->getSettingsManager()->get(Constants\Setting::TEXT_MSG_ENABLED, $accountId);
    }
}
