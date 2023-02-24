<?php
/**
 * Auction Sort by Consignor Data Loader
 *
 * SAM-5587: Refactor data loader for Auction Sort by Consignor page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 06, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionSortByConsignorForm\Load;

use AuctionLotItem;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class AuctionSortByConsignorDataLoader
 */
class AuctionSortByConsignorDataLoader extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param int|null $consignorUserId null means don't filter by consignor user
     * @param bool $isReadOnlyDb
     * @return AuctionLotItem[]
     */
    public function loadAuctionLots(int $auctionId, ?int $consignorUserId, bool $isReadOnlyDb = false): array
    {
        $consignorUserId = Cast::toInt($consignorUserId, Constants\Type::F_INT_POSITIVE);
        $repo = $this->prepareAuctionLotRepository($isReadOnlyDb)
            ->filterAuctionId($auctionId);
        if ($consignorUserId) {
            $repo->joinLotItemFilterConsignorId($consignorUserId);
        }
        $repo->joinLotItemOrderByName();
        return $repo->loadEntities();
    }

    /**
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return int[] - return value of Consignor Sort
     */
    public function loadConsignorSort(int $auctionId, bool $isReadOnlyDb = false): array
    {
        $repo = $this->prepareAuctionLotRepository($isReadOnlyDb)
            ->enableDistinct(true)
            ->filterAuctionId($auctionId)
            ->joinConsignorUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->orderByLotNum()
            ->orderByLotNumExt()
            ->orderByLotNumPrefix()
            ->select(["li.consignor_id"]);
        $rows = $repo->loadRows();
        return ArrayCast::arrayColumnInt($rows, 'consignor_id', Constants\Type::F_INT_POSITIVE);
    }

    /**
     * @param bool $isReadOnlyDb
     * @return AuctionLotItemReadRepository
     */
    protected function prepareAuctionLotRepository(bool $isReadOnlyDb = false): AuctionLotItemReadRepository
    {
        return $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->joinLotItemFilterActive(true)
            ->joinConsignorUser()
            ->joinConsignorUserInfo()
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses);
    }
}
