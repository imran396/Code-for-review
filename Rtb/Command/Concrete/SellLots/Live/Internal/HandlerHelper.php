<?php
/**
 * SAM-6527: Rtb refactor SellLots command
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\SellLots\Live\Internal;

use AuctionLotItem;
use Sam\Core\Service\CustomizableClass;
use LotItem;
use RtbCurrent;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Load\PositionalAuctionLotLoaderAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Rtb\Group\GroupingHelperAwareTrait;

/**
 * Class Helper
 * @package Sam\Rtb
 */
class HandlerHelper extends CustomizableClass
{
    use AuctionLotLoaderAwareTrait;
    use GroupingHelperAwareTrait;
    use LotItemLoaderAwareTrait;
    use PositionalAuctionLotLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Implements next auction lot detection for live auction.
     * Return running lot if no next lots were found.
     * @param RtbCurrent $rtbCurrent
     * @param AuctionLotItem $currentAuctionLot running lot
     * @return AuctionLotItem|null
     * @internal
     */
    public function findNextAuctionLot(
        RtbCurrent $rtbCurrent,
        AuctionLotItem $currentAuctionLot
    ): ?AuctionLotItem {
        $auctionId = $rtbCurrent->AuctionId;
        if ($this->canSellLot($currentAuctionLot)) {
            $nextAuctionLot = $currentAuctionLot;
        } elseif ($this->getGroupingHelper()->countGroup($auctionId) > 1) {
            $rtbCurrentGroupRecords = $this->getGroupingHelper()->loadGroups($auctionId, 1);
            $nextAuctionLot = $this->getAuctionLotLoader()
                ->load($rtbCurrentGroupRecords[0]->LotItemId, $auctionId);
        } else {
            $nextAuctionLot = $this->getPositionalAuctionLotLoader()
                ->loadNextLot($auctionId, $rtbCurrent->LotItemId, true);
        }
        return $nextAuctionLot;
    }

    /**
     * @param AuctionLotItem|null $nextAuctionLot null when next not found
     * @param LotItem|null $runningLotItem null when running absent
     * @return LotItem|null
     * @internal
     */
    public function findNextLotItemByAuctionLot(
        ?AuctionLotItem $nextAuctionLot = null,
        ?LotItem $runningLotItem = null
    ): ?LotItem {
        return $nextAuctionLot
            ? $this->getLotItemLoader()->load($nextAuctionLot->LotItemId)
            : $runningLotItem;
    }

    /**
     * Checks, if lot can still be sold
     * @param AuctionLotItem $auctionLot
     * @return bool
     */
    protected function canSellLot(AuctionLotItem $auctionLot): bool
    {
        return $auctionLot->isActiveOrUnsold();
    }
}
