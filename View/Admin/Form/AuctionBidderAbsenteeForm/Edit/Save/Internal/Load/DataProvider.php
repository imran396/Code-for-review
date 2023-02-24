<?php
/**
 * SAM-9530: "User Absentee Bid" page - extract logic and cover with unit test for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Edit\Save\Internal\Load;

use AbsenteeBid;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Bidding\AbsenteeBid\Load\AbsenteeBidLoaderAwareTrait;
use Sam\Core\AuctionLot\LotNo\Parse\LotNoParsed;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataProvider
 * @package Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Edit\Save\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use AbsenteeBidLoaderAwareTrait;
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
     * Load array of selected fields of auction lot filtered by lotNoParsed
     * @param array $select
     * @param LotNoParsed $lotNoParsed
     * @param int|null $auctionId null leads to empty array result
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadSelectedByLotNoParsed(
        array $select,
        LotNoParsed $lotNoParsed,
        ?int $auctionId,
        bool $isReadOnlyDb = false
    ): array {
        return $this->getAuctionLotLoader()->loadSelectedByLotNoParsed($select, $lotNoParsed, $auctionId, $isReadOnlyDb);
    }

    /**
     * @param int $absenteeBidId
     * @param bool $isReadOnlyDb
     * @return AbsenteeBid|null
     */
    public function loadAbsenteeBidById(int $absenteeBidId, bool $isReadOnlyDb = false): ?AbsenteeBid
    {
        return $this->getAbsenteeBidLoader()->loadById($absenteeBidId, $isReadOnlyDb);
    }
}
