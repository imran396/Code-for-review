<?php
/**
 * Send emails to bidders about lot changing
 *
 * SAM-4015: Auction Lot and Lot Item Entity Makers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 8, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Modify\NotifyBidder;

use AuctionLotItem;
use DateTime;
use Email_Template;
use LotItem;
use Sam\ActionQueue\ActionQueueManagerAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Modify\NotifyBidder\Load\DataLoader;
use Sam\Lot\Render\LotRenderer;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionLotAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;
use Sam\Storage\WriteRepository\Entity\ActionQueue\ActionQueueWriteRepositoryAwareTrait;

/**
 * Class LotBiddersNotifier
 * @package Sam\Lot\Modify
 */
class LotBidderNotifier extends CustomizableClass
{
    use ActionQueueManagerAwareTrait;
    use ActionQueueWriteRepositoryAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use LotItemAwareTrait;
    use SettingsManagerAwareTrait;

    /** @var string[] */
    public array $lotItemCheckingFields = ['Name', 'Description', 'LowEstimate', 'HighEstimate', 'Warranty'];
    /** @var string[] */
    public array $auctionLotCheckingFields = ['LotNum', 'LotNumExt', 'LotNumPrefix'];

    /**
     * @var string
     */
    public string $auctionType = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check modification for auction lot
     * @param AuctionLotItem $auctionLot
     * @return bool
     */
    public function checkModificationForAuctionLot(AuctionLotItem $auctionLot): bool
    {
        $this->setAuctionLot($auctionLot);

        foreach ($this->auctionLotCheckingFields as $field) {
            if (
                array_key_exists($field, $auctionLot->__Modified)
                && $auctionLot->$field
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check modification for lot item
     * @param LotItem $lotItem
     * @return bool
     */
    public function checkModificationForLotItem(LotItem $lotItem): bool
    {
        $this->setLotItem($lotItem);

        return (bool)array_intersect($this->lotItemCheckingFields, array_keys($lotItem->__Modified));
    }

    /**
     * Email bidders about lot changes
     * @param int $editorUserId
     */
    public function notify(int $editorUserId): void
    {
        if ($this->getLotItem()) {
            $auctionLot = $this->getAuctionLotLoader()->loadRecentByLotItemId($this->getLotItemId());
            $this->setAuctionLot($auctionLot);
        }
        $auctionLot = $this->getAuctionLot();
        if (!$auctionLot) {
            log_error(
                "Available auction lot item not found, when notify lot bidder"
                . composeSuffix(['li' => $this->getLotItemId(), 'ali' => $this->getAuctionLotId()])
            );
            return;
        }

        $accountId = $auctionLot->AccountId;

        $isAbsenteeBidLotNotification = (bool)$this->getSettingsManager()->get(Constants\Setting::ABSENTEE_BID_LOT_NOTIFICATION, $accountId);
        if ($isAbsenteeBidLotNotification) {
            $auctionId = $auctionLot->AuctionId;
            $auctionType = $this->auctionType
                ?: $this->getAuctionLoader()
                    ->load($auctionId, true)
                    ->AuctionType;

            $userIds = DataLoader::new()->loadBidsUserId($auctionLot->LotItemId, $auctionId, $auctionType);
            foreach ($userIds as $userId) {
                // Find all the bidders with bid first and then email them with the changes on lot
                $emailTemplate = Email_Template::new()->construct(
                    $accountId,
                    Constants\EmailKey::ABSENTEE_AND_TIMED_BID_LOT_MODIFICATION_NOTIFICATION,
                    $editorUserId,
                    [$userId, $auctionLot],
                    $auctionId
                );
                $identifier = $emailTemplate->getEmail()->getIdentifier();
                if (!$this->isNotified($identifier, $editorUserId)) {
                    $emailTemplate->addToActionQueue(Constants\ActionQueue::LOW);
                }
            }
        }
    }

    /**
     * Is the user already notified. It's possible if both lot item and auction lot fields were changed
     * @param string $identifier Not coded action_queue.identifier field
     * @param int $editorUserId
     * @return bool
     * @throws \Exception
     */
    private function isNotified(string $identifier, int $editorUserId): bool
    {
        $actionQueue = $this->getActionQueueManager()->loadByIdentifier($identifier, true, true);

        // Refresh LotNum if an action in a queue already exists, but LotNum was changed in one block with lotItem data
        if (
            $actionQueue
            && !$this->getLotItem()
            && (new DateTime($actionQueue->CreatedOn))->getTimestamp() === time()
        ) {
            $actionQueue->Data = str_replace($this->getOldLotFullNum(), $this->getNewLotFullNum(), $actionQueue->Data);
            $this->getActionQueueWriteRepository()->saveWithModifier($actionQueue, $editorUserId);
            return true;
        }

        return (bool)$actionQueue;
    }

    /**
     * Get new LotFullNum
     * @return string
     */
    private function getNewLotFullNum(): string
    {
        return LotRenderer::new()->renderLotNo($this->getAuctionLot());
    }

    /**
     * Get old LotFullNum
     * @return string
     */
    private function getOldLotFullNum(): string
    {
        $auctionLot = $this->getAuctionLot();
        if (!$auctionLot) {
            log_error(
                "Available auction lot not found, when notify lot bidder"
                . composeSuffix(['ali' => $this->getAuctionLotId()])
            );
            return '';
        }

        $lotNum = $auctionLot->__Modified['LotNum'] ?? $auctionLot->LotNum;
        $lotNumExt = $auctionLot->__Modified['LotNumExt'] ?? $auctionLot->LotNumExt;
        $lotNumPrefix = $auctionLot->__Modified['LotNumPrefix'] ?? $auctionLot->LotNumPrefix;
        return LotRenderer::new()->makeLotNo($lotNum, $lotNumExt, $lotNumPrefix);
    }
}
