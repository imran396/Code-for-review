<?php
/**
 * Auction Sort by Consignor Updater
 *
 * SAM-5663: Extract update action for Auction Sort by Consignor page at admin side
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 06, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionSortByConsignorForm\Save;

use Auction;
use Sam\AuctionLot\Order\Reorder\AuctionLotReordererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\View\Admin\Form\AuctionSortByConsignorForm\Load\AuctionSortByConsignorDataLoaderCreateTrait;

/**
 * Class AuctionSortByConsignorUpdater
 * @method Auction getAuction()
 */
class AuctionSortByConsignorUpdater extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotReordererAwareTrait;
    use AuctionSortByConsignorDataLoaderCreateTrait;
    use AuctionWriteRepositoryAwareTrait;

    /**
     * @var int[]
     */
    protected array $userIds = [];
    protected bool $isChangedPrimaryOrder = false;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $userIds
     * @return static
     */
    public function setUserIds(array $userIds): static
    {
        $this->userIds = $userIds;
        return $this;
    }

    /**
     * @return int[]
     */
    public function getUserIds(): array
    {
        return $this->userIds;
    }

    /**
     * @param bool $isChangedPrimaryOrder
     * @return static
     */
    public function enableChangedPrimaryOrder(bool $isChangedPrimaryOrder): static
    {
        $this->isChangedPrimaryOrder = $isChangedPrimaryOrder;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasChangedPrimaryOrder(): bool
    {
        return $this->isChangedPrimaryOrder;
    }

    /**
     * Update Auction ordering by Consignor
     * @param int $editorUserId
     */
    public function update(int $editorUserId): void
    {
        $lotNum = 1;
        foreach ($this->getUserIds() as $consignorUserId) {
            $auctionLots = $this->createAuctionSortByConsignorDataLoader()
                ->loadAuctionLots($this->getAuctionId(), $consignorUserId, true);
            foreach ($auctionLots as $auctionLot) {
                $auctionLot->LotNum = $lotNum;
                $auctionLot->LotNumExt = '';
                $auctionLot->LotNumPrefix = '';
                $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
                $lotNum++;
            }
        }
        $auction = $this->getAuction();
        if ($auction->LotOrderPrimaryType !== Constants\Auction::LOT_ORDER_BY_LOT_NUMBER) {
            $auction->LotOrderPrimaryType = Constants\Auction::LOT_ORDER_BY_LOT_NUMBER;
            if ($auction->LotOrderSecondaryType === Constants\Auction::LOT_ORDER_BY_LOT_NUMBER) {
                $auction->LotOrderSecondaryType = Constants\Auction::LOT_ORDER_BY_NONE;
            }
            if ($auction->LotOrderTertiaryType === Constants\Auction::LOT_ORDER_BY_LOT_NUMBER) {
                $auction->LotOrderTertiaryType = Constants\Auction::LOT_ORDER_BY_NONE;
            }
            if ($auction->LotOrderQuaternaryType === Constants\Auction::LOT_ORDER_BY_LOT_NUMBER) {
                $auction->LotOrderQuaternaryType = Constants\Auction::LOT_ORDER_BY_NONE;
            }
            $this->getAuctionWriteRepository()->saveWithModifier($auction, $editorUserId);
            $this->enableChangedPrimaryOrder(true);
        }
        $this->getAuctionLotReorderer()->reorder($auction, $editorUserId);
    }
}
