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

namespace Sam\Rtb\Command\Concrete\SellLots\Hybrid\Internal;

use AuctionLotItem;
use Sam\Core\Service\CustomizableClass;
use LotItem;
use RtbCurrent;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Rtb\Command\Helper\Base\AbstractRtbCommandHelper;
use Sam\Rtb\Group\GroupingHelperAwareTrait;

/**
 * Class Helper
 * @package Sam\Rtb
 */
class HandlerHelper extends CustomizableClass
{
    use LotItemLoaderAwareTrait;
    use GroupingHelperAwareTrait;
    use AuctionLotLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Search for next upcoming lot item. Return null if there is no next lot
     * @param RtbCurrent $rtbCurrent
     * @param AuctionLotItem $currentAuctionLot
     * @param AbstractRtbCommandHelper $commandHelper
     * @return LotItem|null
     */
    public function findNextLotItem(
        RtbCurrent $rtbCurrent,
        AuctionLotItem $currentAuctionLot,
        AbstractRtbCommandHelper $commandHelper
    ): ?LotItem {
        $nextAuctionLot = $this->findNextAuctionLot($rtbCurrent, $currentAuctionLot, $commandHelper);
        return $nextAuctionLot
            ? $this->getLotItemLoader()->load($nextAuctionLot->LotItemId)
            : null;
    }

    /**
     * Implements next lot detection for hybrid auction.
     * Return null if no lots were found.
     * @param RtbCurrent $rtbCurrent
     * @param AuctionLotItem $currentAuctionLot
     * @param AbstractRtbCommandHelper $commandHelper
     * @return AuctionLotItem|null
     */
    protected function findNextAuctionLot(
        RtbCurrent $rtbCurrent,
        AuctionLotItem $currentAuctionLot,
        AbstractRtbCommandHelper $commandHelper
    ): ?AuctionLotItem {
        if ($this->canSellLot($currentAuctionLot)) {
            $nextAuctionLot = $currentAuctionLot;
        } elseif ($this->getGroupingHelper()->countGroup($rtbCurrent->AuctionId) > 1) {
            $rtbCurrentGroupRecords = $this->getGroupingHelper()->loadGroups($rtbCurrent->AuctionId, 1);
            $nextAuctionLot = $this->getAuctionLotLoader()->load($rtbCurrentGroupRecords[0]->LotItemId, $rtbCurrent->AuctionId);
        } else {
            $nextAuctionLot = $commandHelper->findNextAuctionLot($rtbCurrent);
        }
        return $nextAuctionLot;
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
