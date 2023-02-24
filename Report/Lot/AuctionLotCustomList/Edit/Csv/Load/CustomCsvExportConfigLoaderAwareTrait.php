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
 * Trait CustomCsvExportConfigLoaderAwareTrait
 * @package Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Load
 */
trait CustomCsvExportConfigLoaderAwareTrait
{
    protected ?CustomCsvExportConfigLoader $customCsvExportConfigLoader = null;

    /**
     * @return CustomCsvExportConfigLoader
     */
    protected function getCustomCsvExportConfigLoader(): CustomCsvExportConfigLoader
    {
        if ($this->customCsvExportConfigLoader === null) {
            $this->customCsvExportConfigLoader = CustomCsvExportConfigLoader::new();
        }
        return $this->customCsvExportConfigLoader;
    }

    /**
     * @param CustomCsvExportConfigLoader $customCsvExportConfigLoader
     * @return static
     */
    public function setCustomCsvExportConfigLoader(CustomCsvExportConfigLoader $customCsvExportConfigLoader): static
    {
        $this->customCsvExportConfigLoader = $customCsvExportConfigLoader;
        return $this;
    }
}
