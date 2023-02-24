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


use CustomCsvExportConfig;
use Sam\Core\Service\CustomizableClass;
use Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Load\Dto\CustomCsvExportConfigDto;
use Sam\Storage\ReadRepository\Entity\CustomCsvExportConfig\CustomCsvExportConfigReadRepositoryCreateTrait;

/**
 * Class CustomCsvExportConfigLoader
 * @package Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Load
 */
class CustomCsvExportConfigLoader extends CustomizableClass
{
    use CustomCsvExportConfigReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load a CustomCsvExportConfig from PK Info
     * @param int|null $id
     * @param bool $isReadOnlyDb query to read-only db
     * @return CustomCsvExportConfig|null
     */
    public function load(?int $id, bool $isReadOnlyDb = false): ?CustomCsvExportConfig
    {
        if (!$id) {
            return null;
        }

        $customCsvExportConfig = $this->createCustomCsvExportConfigReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($id)
            ->loadEntity();
        return $customCsvExportConfig;
    }

    /**
     * Load a CustomCsvExportConfig from PK Info
     * @param int|null $id
     * @param array $select
     * @param bool $isReadOnlyDb query to read-only db
     * @return CustomCsvExportConfigDto|null
     */
    public function loadDto(?int $id, array $select = [], bool $isReadOnlyDb = false): ?CustomCsvExportConfigDto
    {
        if (!$id) {
            return null;
        }

        $row = $this->createCustomCsvExportConfigReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($id)
            ->select($select)
            ->loadRow();
        if (!$row) {
            return null;
        }
        $dto = CustomCsvExportConfigDto::new()->fromDbRow($row);
        return $dto;
    }

    /**
     * @param array $select
     * @param bool $isReadOnlyDb
     * @return CustomCsvExportConfigDto[]
     */
    public function loadDtos(array $select = [], bool $isReadOnlyDb = false): array
    {
        $rows = $this->createCustomCsvExportConfigReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->select($select)
            ->loadRows();

        $dtos = array_map(
            static function (array $row) {
                return CustomCsvExportConfigDto::new()->fromDbRow($row);
            },
            $rows
        );
        return $dtos;
    }
}
