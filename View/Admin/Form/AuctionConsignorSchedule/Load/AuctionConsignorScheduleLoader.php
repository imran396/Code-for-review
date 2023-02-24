<?php
/**
 * SAM-5667: Extract logic for Auction lot info for Consignor Schedule page at admin side
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

namespace Sam\View\Admin\Form\AuctionConsignorSchedule\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class AuctionConsignorScheduleLoader
 * @package Sam\View\Admin\Form\AuctionConsignorSchedule\Load
 */
class AuctionConsignorScheduleLoader extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $consignorUserId
     * @param int|null $auctionId
     * @param bool $readOnlyDb
     * @return array
     */
    public function load(int $consignorUserId, int $auctionId = null, bool $readOnlyDb = false): array
    {
        return $this
            ->prepareRepository($consignorUserId, $auctionId, $readOnlyDb)
            ->loadRows();
    }

    /**
     * @param int $consignorUserId
     * @param int|null $auctionId
     * @param bool $readOnlyDb
     * @return AuctionLotItemReadRepository
     */
    private function prepareRepository(
        int $consignorUserId,
        int $auctionId = null,
        bool $readOnlyDb = false
    ): AuctionLotItemReadRepository {
        $repository = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($readOnlyDb)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAuction()
            ->joinConsignorUser()
            ->joinLotItem()
            ->joinLotItemFilterConsignorId($consignorUserId)
            ->select(
                [
                    'ali.quantity AS quantity',
                    'COALESCE(
                        ali.quantity_digits, 
                        li.quantity_digits, 
                        (SELECT lc.quantity_digits
                         FROM lot_category lc
                           INNER JOIN lot_item_category lic ON lc.id = lic.lot_category_id
                         WHERE lic.lot_item_id = li.id
                           AND lc.active = 1
                         ORDER BY lic.id
                         LIMIT 1), 
                        (SELECT seta.quantity_digits FROM setting_auction seta WHERE seta.account_id = li.account_id)
                    ) as quantity_scale',
                    'li.account_id',
                    'li.name AS name',
                    'uc.customer_no AS customer_no',
                    'uc.username AS username',
                    'a.sale_num AS sale_num',
                ]
            );
        if ($auctionId !== null) {
            $repository->filterAuctionId($auctionId);
        }
        return $repository;
    }
}
