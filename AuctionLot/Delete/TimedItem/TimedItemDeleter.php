<?php
/**
 * SAM-6697: Lot deleters for v3.5 https://bidpath.atlassian.net/browse/SAM-6697
 * SAM-4977: Timed online item deleter
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           31.03.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\AuctionLot\Delete\TimedItem;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\TimedOnlineItem\TimedOnlineItemReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\TimedOnlineItem\TimedOnlineItemWriteRepositoryAwareTrait;
use TimedOnlineItem;

/**
 * Class TimedItemDeleter
 */
class TimedItemDeleter extends CustomizableClass
{
    use TimedOnlineItemReadRepositoryCreateTrait;
    use TimedOnlineItemWriteRepositoryAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete timed item with all dependencies
     * @param TimedOnlineItem $timedItem
     * @param int $editorUserId
     */
    public function delete(TimedOnlineItem $timedItem, int $editorUserId): void
    {
        $this->getTimedOnlineItemWriteRepository()->deleteWithModifier($timedItem, $editorUserId);
        $this->log($timedItem, $editorUserId);
    }

    /**
     * Delete timed item with all dependencies by its id
     * @param int $timedItemId
     * @param int $editorUserId
     */
    public function deleteById(int $timedItemId, int $editorUserId): void
    {
        $timedItem = $this->createTimedOnlineItemReadRepository()
            ->filterId($timedItemId)
            ->loadEntity();
        if ($timedItem) {
            $this->delete($timedItem, $editorUserId);
        }
    }

    /**
     * Delete timed_online_info records for passed auction lot
     *
     * @param int $lotItemId
     * @param int $auctionId
     * @param int $editorUserId
     */
    public function deleteByLotItemIdAndAuctionId(int $lotItemId, int $auctionId, int $editorUserId): void
    {
        $timedItem = $this->createTimedOnlineItemReadRepository()
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->loadEntity();
        if ($timedItem) {
            $this->delete($timedItem, $editorUserId);
        }
    }

    /**
     * @param TimedOnlineItem $timedItem
     * @param int $editorUserId
     */
    protected function log(TimedOnlineItem $timedItem, int $editorUserId): void
    {
        // TODO: "Timed online item soft-deleted"
        $message = "Timed online item deleted"
            . composeSuffix(['toi' => $timedItem->Id, 'editor u' => $editorUserId]);
        log_debug($message);
    }

}
