<?php
/**
 * SAM-9462: Lot CSV import - fix item# and lot# uniqueness check
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\Internal\UpdatingEntity\DetectByAuctionLot\Internal\Load;

use Sam\AuctionLot\Load\AuctionLotLoader;
use Sam\AuctionLot\LotNo\Parse\LotNoParser;
use Sam\Core\AuctionLot\LotNo\Parse\LotNoParsed;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\Lot\Internal\UpdatingEntity\Common\LotItemIdDetectionResult;
use Sam\Import\Csv\Lot\Internal\UpdatingEntity\DetectByLotItem\InventoryContextLotItemIdDetector;
use Sam\Import\Csv\Read\CsvRow;

/**
 * Class DataLoader
 * @package Sam\Import\Csv\Lot\Internal\UpdatingEntity\DetectByAuctionLot\Internal\Load
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

    public function loadLotItemIdByLotNoParsed(LotNoParsed $lotNoParsed, ?int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        $row = AuctionLotLoader::new()->loadSelectedByLotNoParsed(
            ['ali.lot_item_id'],
            $lotNoParsed,
            $auctionId,
            $isReadOnlyDb
        );
        return Cast::toInt($row['lot_item_id'] ?? null);
    }

    public function detectLotItemIdByCsvRow(CsvRow $row, int $accountId, bool $isReadOnlyDb = false): LotItemIdDetectionResult
    {
        return InventoryContextLotItemIdDetector::new()->detect($row, $accountId, $isReadOnlyDb);
    }

    public function parseLotNo(string $lotNo): LotNoParsed
    {
        return LotNoParser::new()->construct()->parse($lotNo);
    }
}
