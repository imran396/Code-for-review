<?php
/**
 * SAM-5753: Refactor "Bid Book" report
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BidBookReportForm\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class AuctionLotSelectDataLoader
 * @package Sam\View\Admin\Form\BidBookReportForm\Load
 */
class AuctionLotSelectDataLoader extends CustomizableClass
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
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return AuctionLotSelectData[]
     */
    public function load(int $auctionId, bool $isReadOnlyDb = false): array
    {
        $rows = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->joinLotItemFilterActive(true)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->filterAuctionId($auctionId)
            ->orderByOrder()
            ->select(
                [
                    'ali.lot_num',
                    'ali.lot_num_ext',
                    'ali.lot_num_prefix',
                    'ali.order',
                    'li.name',
                ]
            )
            ->loadRows();
        return array_map(AuctionLotSelectData::new()->fromDbRow(...), $rows);
    }
}
