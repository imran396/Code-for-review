<?php
/**
 * SAM-10948: Stacked Tax. Invoice Management pages. Prepare Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Produce\Internal\BulkGroup;

/**
 * Trait BulkGroupLotItemCollectorCreateTrait
 * @package Sam\Invoice\StackedTax\Generate\Produce\Internal\BulkGroup
 */
trait BulkGroupLotItemCollectorCreateTrait
{
    protected ?BulkGroupLotItemCollector $bulkGroupLotItemCollector = null;

    /**
     * @return BulkGroupLotItemCollector
     */
    protected function createBulkGroupLotItemCollector(): BulkGroupLotItemCollector
    {
        return $this->bulkGroupLotItemCollector ?: BulkGroupLotItemCollector::new();
    }

    /**
     * @param BulkGroupLotItemCollector $bulkGroupLotItemCollector
     * @return $this
     * @internal
     */
    public function setBulkGroupLotItemCollector(BulkGroupLotItemCollector $bulkGroupLotItemCollector): static
    {
        $this->bulkGroupLotItemCollector = $bulkGroupLotItemCollector;
        return $this;
    }
}
