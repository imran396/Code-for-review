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

namespace Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Load;


use CustomCsvExportData;
use Sam\Core\Service\CustomizableClass;
use Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Load\Dto\CustomCsvExportDataDto;
use Sam\Storage\ReadRepository\Entity\CustomCsvExportData\CustomCsvExportDataReadRepositoryCreateTrait;

/**
 * Class CustomCsvExportDataLoader
 * @package Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Load
 */
class CustomCsvExportDataLoader extends CustomizableClass
{
    use CustomCsvExportDataReadRepositoryCreateTrait;

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
     * @param bool $isReadOnlyDb
     * @return CustomCsvExportData[]
     */
    public function loadEntities(int $configId, bool $isReadOnlyDb = false): array
    {
        $entities = $this->createCustomCsvExportDataReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterConfigId($configId)
            ->filterActive(true)
            ->loadEntities();
        return $entities;
    }

    /**
     * @param int $configId
     * @param array $select
     * @param bool $isReadOnlyDb
     * @return CustomCsvExportDataDto[]
     */
    public function loadDtos(int $configId, array $select = [], bool $isReadOnlyDb = false): array
    {
        $rows = $this->createCustomCsvExportDataReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterConfigId($configId)
            ->filterActive(true)
            ->select($select)
            ->loadRows();

        $dtos = array_map(
            static function (array $row) {
                return CustomCsvExportDataDto::new()->fromDbRow($row);
            },
            $rows
        );
        return $dtos;
    }
}
