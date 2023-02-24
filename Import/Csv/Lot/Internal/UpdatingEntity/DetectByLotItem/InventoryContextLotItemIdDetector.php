<?php
/**
 * Detects existing lot item by item# found in csv input uploaded out of auction context, i.e. in inventory or when creating new auction.
 *
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\Internal\UpdatingEntity\DetectByLotItem;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\LotItem\ItemNo\Parse\LotItemNoParsed;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\Lot\Internal\UpdatingEntity\DetectByLotItem\Internal\Load\DataProviderCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Import\Csv\Read\CsvRow;
use Sam\Import\Csv\Lot\Internal\UpdatingEntity\Common\LotItemIdDetectionResult as Result;

/**
 * Class InventoryContextLotItemIdDetector
 * @package Sam\Import\Csv\Lot
 */
class InventoryContextLotItemIdDetector extends CustomizableClass
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
     * Find lot item by item No, if it is set
     *
     * @param CsvRow $row
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return Result
     */
    public function detect(CsvRow $row, int $accountId, bool $isReadOnlyDb = false): Result
    {
        $itemNoParsed = $this->parseItemNo($row);
        if ($itemNoParsed->ok()) {
            $lotItemId = $this->createDataProvider()
                ->loadLotItemIdByItemNoParsed($itemNoParsed, $accountId, $isReadOnlyDb);
            if ($lotItemId) {
                return Result::new()->constructByItemNo($lotItemId);
            }
        }

        return Result::new()->constructNotFound();
    }

    /**
     * @param CsvRow $row
     * @return LotItemNoParsed
     */
    protected function parseItemNo(CsvRow $row): LotItemNoParsed
    {
        if ($this->cfg()->get('core->lot->itemNo->concatenated')) {
            $itemNo = $row->getCell(Constants\Csv\Lot::ITEM_FULL_NUMBER);
            $itemNoParsed = $this->createDataProvider()->parseItemNo($itemNo);
        } else {
            $itemNoParsed = LotItemNoParsed::new()->construct(
                Cast::toInt($row->getCell(Constants\Csv\Lot::ITEM_NUM)),
                $row->getCell(Constants\Csv\Lot::ITEM_NUM_EXT)
            );
        }
        return $itemNoParsed;
    }
}
