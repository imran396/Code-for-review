<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\Internal\UpdatingEntity\DetectByLotItem;

/**
 * Trait LotItemImportCsvItemIdDetectorCreateTrait
 * @package Sam\Import\Csv\Lot
 */
trait InventoryContextLotItemIdDetectorCreateTrait
{
    /**
     * @var InventoryContextLotItemIdDetector|null
     */
    protected ?InventoryContextLotItemIdDetector $inventoryContextLotItemIdDetector = null;

    /**
     * @return InventoryContextLotItemIdDetector
     */
    protected function createInventoryContextLotItemIdDetector(): InventoryContextLotItemIdDetector
    {
        return $this->inventoryContextLotItemIdDetector ?: InventoryContextLotItemIdDetector::new();
    }

    /**
     * @param InventoryContextLotItemIdDetector $detector
     * @return static
     * @internal
     */
    public function setInventoryContextLotItemIdDetector(InventoryContextLotItemIdDetector $detector): static
    {
        $this->inventoryContextLotItemIdDetector = $detector;
        return $this;
    }
}
