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

namespace Sam\Report\Lot\AuctionLotCustomList\Edit\Validate;


/**
 * Trait CustomCsvExportConfigExistenceCheckerCreateTrait
 * @package Sam\Report\Lot\AuctionLotCustomList\Edit\Validate
 */
trait CustomCsvExportConfigExistenceCheckerCreateTrait
{
    protected ?CustomCsvExportConfigExistenceChecker $customCsvExportConfigExistenceChecker = null;

    /**
     * @return CustomCsvExportConfigExistenceChecker
     */
    protected function createCustomCsvExportConfigExistenceChecker(): CustomCsvExportConfigExistenceChecker
    {
        return $this->customCsvExportConfigExistenceChecker ?: CustomCsvExportConfigExistenceChecker::new();
    }

    /**
     * @param CustomCsvExportConfigExistenceChecker $customCsvExportConfigExistenceChecker
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setCustomCsvExportConfigExistenceChecker(CustomCsvExportConfigExistenceChecker $customCsvExportConfigExistenceChecker): static
    {
        $this->customCsvExportConfigExistenceChecker = $customCsvExportConfigExistenceChecker;
        return $this;
    }
}
