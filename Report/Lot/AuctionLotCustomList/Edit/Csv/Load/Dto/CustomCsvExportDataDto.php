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

namespace Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Load\Dto;


use Sam\Core\Service\CustomizableClass;

/**
 * Class CustomCsvExportDataDto
 * @package Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Load\Dto
 */
class CustomCsvExportDataDto extends CustomizableClass
{
    public readonly int $id;
    public readonly int $configId;
    public readonly string $fieldIndex;
    public readonly string $fieldName;
    public readonly float $fieldOrder;
    public readonly bool $active;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $row
     * @return static
     */
    public function fromDbRow(array $row): static
    {
        $dto = static::new();
        $dto->active = (bool)($row['active'] ?? null);
        $dto->configId = (int)($row['config_id'] ?? null);
        $dto->fieldIndex = (string)($row['field_index'] ?? null);
        $dto->fieldName = (string)($row['field_name'] ?? null);
        $dto->fieldOrder = (float)($row['field_order'] ?? null);
        $dto->id = (int)($row['id'] ?? null);
        return $dto;
    }
}
