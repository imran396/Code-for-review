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

namespace Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Load;


/**
 * Trait CustomCsvExportDataLoaderAwareTrait
 * @package Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Load
 */
trait CustomCsvExportDataLoaderAwareTrait
{
    protected ?CustomCsvExportDataLoader $customCsvExportDataLoader = null;

    /**
     * @return CustomCsvExportDataLoader
     */
    protected function getCustomCsvExportDataLoader(): CustomCsvExportDataLoader
    {
        if ($this->customCsvExportDataLoader === null) {
            $this->customCsvExportDataLoader = CustomCsvExportDataLoader::new();
        }
        return $this->customCsvExportDataLoader;
    }

    /**
     * @param CustomCsvExportDataLoader $customCsvExportDataLoader
     * @return static
     */
    public function setCustomCsvExportDataLoader(CustomCsvExportDataLoader $customCsvExportDataLoader): static
    {
        $this->customCsvExportDataLoader = $customCsvExportDataLoader;
        return $this;
    }
}
