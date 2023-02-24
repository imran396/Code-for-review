<?php
/**
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

namespace Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Calculate;


/**
 * Trait CustomCsvExportFieldDetectorCreateTrait
 * @package Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Calculate
 */
trait CustomCsvExportFieldDetectorCreateTrait
{
    protected ?CustomCsvExportFieldDetector $customCsvExportFieldDetector = null;

    /**
     * @return CustomCsvExportFieldDetector
     */
    protected function createCustomCsvExportFieldDetector(): CustomCsvExportFieldDetector
    {
        return $this->customCsvExportFieldDetector ?: CustomCsvExportFieldDetector::new();
    }

    /**
     * @param CustomCsvExportFieldDetector $customCsvExportFieldDetector
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setCustomCsvExportFieldDetector(CustomCsvExportFieldDetector $customCsvExportFieldDetector): static
    {
        $this->customCsvExportFieldDetector = $customCsvExportFieldDetector;
        return $this;
    }
}
