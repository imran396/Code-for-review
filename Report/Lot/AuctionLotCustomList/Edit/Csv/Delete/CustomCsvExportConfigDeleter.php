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

namespace Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Delete;


use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\CustomCsvExportConfig\CustomCsvExportConfigReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\CustomCsvExportData\CustomCsvExportDataReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\CustomCsvExportConfig\CustomCsvExportConfigWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\CustomCsvExportData\CustomCsvExportDataWriteRepositoryAwareTrait;

/**
 * Class CustomCsvExportConfigDeleter
 * @package Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Delete
 */
class CustomCsvExportConfigDeleter extends CustomizableClass
{
    use CustomCsvExportConfigReadRepositoryCreateTrait;
    use CustomCsvExportConfigWriteRepositoryAwareTrait;
    use CustomCsvExportDataReadRepositoryCreateTrait;
    use CustomCsvExportDataWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $configId
     * @param int $editorUserId
     */
    public function delete(int $configId, int $editorUserId): void
    {
        $this->markConfigDataAsDeleted($configId, $editorUserId);
        $this->markConfigAsDeleted($configId, $editorUserId);
    }

    /**
     * @param int $configId
     * @param int $editorUserId
     */
    protected function markConfigAsDeleted(int $configId, int $editorUserId): void
    {
        $customCsvExportConfig = $this->createCustomCsvExportConfigReadRepository()
            ->filterId($configId)
            ->loadEntity();
        $customCsvExportConfig->Active = false;
        $this->getCustomCsvExportConfigWriteRepository()->saveWithModifier($customCsvExportConfig, $editorUserId);
    }

    /**
     * @param int $configId
     * @param int $editorUserId
     */
    protected function markConfigDataAsDeleted(int $configId, int $editorUserId): void
    {
        $exportsData = $this->createCustomCsvExportDataReadRepository()
            ->filterConfigId($configId)
            ->loadEntities();
        foreach ($exportsData as $exportData) {
            $exportData->Active = false;
            $this->getCustomCsvExportDataWriteRepository()->saveWithModifier($exportData, $editorUserId);
        }
    }
}
