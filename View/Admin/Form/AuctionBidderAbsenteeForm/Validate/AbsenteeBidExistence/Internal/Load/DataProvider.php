<?php
/**
 * SAM-9530: "User Absentee Bid" page - extract existing bid detection logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Validate\AbsenteeBidExistence\Internal\Load;

use Sam\AuctionLot\Load\AuctionLotLoader;
use Sam\Bidding\AbsenteeBid\Validate\AbsenteeBidExistenceChecker;
use Sam\Core\AuctionLot\LotNo\Parse\LotNoParsed;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataProvider
 * @package
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadLotItemId(
        LotNoParsed $lotNoParsed,
        int $auctionId,
        bool $isReadOnlyDb = false
    ): ?int {
        $row = AuctionLotLoader::new()->loadSelectedByLotNoParsed(
            ['ali.lot_item_id'],
            $lotNoParsed,
            $auctionId,
            $isReadOnlyDb
        );
        $lotItemId = Cast::toInt($row['lot_item_id'] ?? null);
        return $lotItemId;
    }

    public function existAbsenteeBid(
        ?int $lotItemId,
        ?int $auctionId,
        ?int $userId,
        bool $isReadOnlyDb = false
    ): bool {
        return AbsenteeBidExistenceChecker::new()->existForLotAndUser($lotItemId, $auctionId, $userId, $isReadOnlyDb);
    }
}
