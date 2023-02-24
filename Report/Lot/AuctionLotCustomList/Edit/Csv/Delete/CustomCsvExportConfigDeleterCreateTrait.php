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

namespace Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Delete;


/**
 * Trait CustomCsvExportConfigDeleterCreateTrait
 * @package Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Delete
 */
trait CustomCsvExportConfigDeleterCreateTrait
{
    protected ?CustomCsvExportConfigDeleter $customCsvExportConfigDeleter = null;

    /**
     * @return CustomCsvExportConfigDeleter
     */
    protected function createCustomCsvExportConfigDeleter(): CustomCsvExportConfigDeleter
    {
        return $this->customCsvExportConfigDeleter ?: CustomCsvExportConfigDeleter::new();
    }

    /**
     * @param CustomCsvExportConfigDeleter $customCsvExportConfigDeleter
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setCustomCsvExportConfigDeleter(CustomCsvExportConfigDeleter $customCsvExportConfigDeleter): static
    {
        $this->customCsvExportConfigDeleter = $customCsvExportConfigDeleter;
        return $this;
    }
}
