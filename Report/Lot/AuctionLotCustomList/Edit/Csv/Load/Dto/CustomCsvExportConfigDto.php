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
 * Class CustomCsvExportConfigDto
 * @package Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Load\Dto
 */
class CustomCsvExportConfigDto extends CustomizableClass
{
    public readonly int $id;
    public readonly int $accountId;
    public readonly string $name;
    public readonly bool $active;
    public readonly bool $imageWebLinks;
    public readonly int $imageSeparateColumns;
    public readonly int $categoriesSeparateColumns;

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
        $dto->accountId = (int)($row['account_id'] ?? null);
        $dto->active = (bool)($row['active'] ?? null);
        $dto->categoriesSeparateColumns = (int)($row['category_separate_columns'] ?? null);
        $dto->id = (int)($row['id'] ?? null);
        $dto->imageSeparateColumns = (int)($row['image_separate_columns'] ?? null);
        $dto->imageWebLinks = (bool)($row['image_web_links'] ?? null);
        $dto->name = (string)($row['name'] ?? null);
        return $dto;
    }
}
