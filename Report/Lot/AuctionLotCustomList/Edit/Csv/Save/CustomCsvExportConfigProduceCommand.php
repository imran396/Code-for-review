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

namespace Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Save;


use Sam\Core\Service\CustomizableClass;

/**
 * Class CustomCsvExportConfigProduceCommand
 * @package Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Save
 */
class CustomCsvExportConfigProduceCommand extends CustomizableClass
{
    public readonly ?int $configId;
    public readonly string $name;
    public readonly bool $isImageWebLinks;
    public readonly int $imageSeparateColumnsQty;
    public readonly int $categoriesSeparateColumnsQty;
    public array $fieldConfigs = [];
    public readonly int $editorUserId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $configId
     * @param string $name
     * @param bool $isImageWebLinks
     * @param int $imageSeparateColumnsQty
     * @param int $categoriesSeparateColumnsQty
     * @param int $editorUserId
     * @return static
     */
    public function construct(
        ?int $configId,
        string $name,
        bool $isImageWebLinks,
        int $imageSeparateColumnsQty,
        int $categoriesSeparateColumnsQty,
        int $editorUserId
    ): static {
        $this->configId = $configId;
        $this->name = $name;
        $this->isImageWebLinks = $isImageWebLinks;
        $this->imageSeparateColumnsQty = $imageSeparateColumnsQty;
        $this->categoriesSeparateColumnsQty = $categoriesSeparateColumnsQty;
        $this->editorUserId = $editorUserId;
        return $this;
    }

    /**
     * @param string $index
     * @param string $name
     * @param float $order
     * @return static
     */
    public function addFieldConfig(string $index, string $name, float $order): static
    {
        $this->fieldConfigs[$index] = [
            'name' => $name,
            'order' => $order
        ];
        return $this;
    }
}
