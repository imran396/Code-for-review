<?php
/**
 * SAM-6527: Rtb refactor SellLots command
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\SellLots\Base\Internal\Load;

use AuctionLotItem;
use Sam\Core\Service\CustomizableClass;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Rtb\Command\Concrete\SellLots\Base\SellLotsCommand;
use Sam\Rtb\Group\GroupingHelperAwareTrait;

/**
 * Class DataLoader
 * @package Sam\Rtb
 */
class DataLoader extends CustomizableClass
{
    use AuctionLotLoaderAwareTrait;
    use GroupingHelperAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Collect lots that must be sold, specialized for Clerk console side
     * @param SellLotsCommand $command
     * @param AuctionLotItem $currentAuctionLot
     * @return array
     */
    public function collectAuctionLotsForSale(SellLotsCommand $command, AuctionLotItem $currentAuctionLot): array
    {
        if ($command->onlySellRunningLot) {
            return [$currentAuctionLot];
        }

        $auctionId = $currentAuctionLot->AuctionId;
        $quantity = $command->quantity;
        $lotItemIdList = $command->lotItemIdList;
        // when $quantity is not set, means "Cancel" is pressed
        $auctionLots = [];
        if ($quantity > 0) {
            $rtbCurrentGroupRecords = $this->getGroupingHelper()->loadGroups($auctionId, $quantity);
            $lotItemIds = ArrayHelper::toArrayByProperty($rtbCurrentGroupRecords, 'LotItemId');
            $auctionLots = $this->getAuctionLotLoader()->loadByLotItemIds($lotItemIds, $auctionId);
        } elseif ($lotItemIdList !== '') {  // $rtbCurrent->LotGroup === Constants\Rtb::GROUP_CHOICE
            $lotItemIds = ArrayCast::castInt(explode(',', $lotItemIdList));
            $auctionLots = $this->getAuctionLotLoader()->loadByLotItemIds($lotItemIds, $auctionId);
        }
        $auctionLots = array_filter($auctionLots);
        return $auctionLots;
    }
}
