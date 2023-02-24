<?php
/**
 * SAM-813: Custom CSV export
 * SAM-6546: Refactor "Custom CSV Export" report management logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct. 04, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Save;


/**
 * Trait CustomCsvExportConfigProducerCreateTrait
 * @package Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Save
 */
trait CustomCsvExportConfigProducerCreateTrait
{
    protected ?CustomCsvExportConfigProducer $customCsvExportConfigProducer = null;

    /**
     * @return CustomCsvExportConfigProducer
     */
    protected function createCustomCsvExportConfigProducer(): CustomCsvExportConfigProducer
    {
        return $this->customCsvExportConfigProducer ?: CustomCsvExportConfigProducer::new();
    }

    /**
     * @param CustomCsvExportConfigProducer $customCsvExportConfigProducer
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setCustomCsvExportConfigProducer(CustomCsvExportConfigProducer $customCsvExportConfigProducer): static
    {
        $this->customCsvExportConfigProducer = $customCsvExportConfigProducer;
        return $this;
    }
}
