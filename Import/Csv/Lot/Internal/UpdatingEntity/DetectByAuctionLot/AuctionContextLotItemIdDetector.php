<?php
/**
 * Detects existing lot item by lot# or item# found in csv input uploaded in known auction context.
 *
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\Internal\UpdatingEntity\DetectByAuctionLot;

use Sam\Core\AuctionLot\LotNo\Parse\LotNoParsed;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\Lot\Internal\UpdatingEntity\DetectByAuctionLot\Internal\Load\DataProviderCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Import\Csv\Read\CsvRow;
use Sam\Import\Csv\Lot\Internal\UpdatingEntity\Common\LotItemIdDetectionResult as Result;

/**
 * Class AuctionLotImportCsvItemIdDetector
 * @package Sam\Import\Csv\Lot\AuctionLot
 */
class AuctionContextLotItemIdDetector extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Find lot item by item No or if it was not found by lot No
     *
     * @param CsvRow $row
     * @param int|null $auctionId
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return Result
     */
    public function detect(CsvRow $row, ?int $auctionId, int $accountId, bool $isReadOnlyDb = false): Result
    {
        $dataProvider = $this->createDataProvider();
        $lotItemIdDetectionResult = $dataProvider->detectLotItemIdByCsvRow($row, $accountId, $isReadOnlyDb);
        if ($lotItemIdDetectionResult->isFoundByItemNo()) {
            return $lotItemIdDetectionResult;
        }

        if ($auctionId) {
            $lotNoParsed = $this->parseLotNo($row);
            if ($lotNoParsed->ok()) {
                $lotItemId = $dataProvider->loadLotItemIdByLotNoParsed($lotNoParsed, $auctionId, $isReadOnlyDb);
                if ($lotItemId) {
                    return Result::new()->constructByLotNo($lotItemId);
                }
            }
        }

        return Result::new()->constructNotFound();
    }

    /**
     * @param CsvRow $row
     * @return LotNoParsed
     */
    protected function parseLotNo(CsvRow $row): LotNoParsed
    {
        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            $lotNo = $row->getCell(Constants\Csv\Lot::LOT_FULL_NUMBER);
            $lotNoParsed = $this->createDataProvider()->parseLotNo($lotNo);
        } else {
            $lotNoParsed = LotNoParsed::new()->construct(
                Cast::toInt($row->getCell(Constants\Csv\Lot::LOT_NUM)),
                $row->getCell(Constants\Csv\Lot::LOT_NUM_EXT),
                $row->getCell(Constants\Csv\Lot::LOT_NUM_PREFIX)
            );
        }
        return $lotNoParsed;
    }
}
