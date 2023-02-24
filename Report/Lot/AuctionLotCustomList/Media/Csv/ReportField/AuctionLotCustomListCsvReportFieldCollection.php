<?php
/**
 * SAM-4644: Refactor "Custom CSV Export" report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug. 17, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\AuctionLotCustomList\Media\Csv\ReportField;

use ArrayIterator;
use Closure;
use CustomCsvExportData;
use Sam\Core\Service\CustomizableClass;
use IteratorAggregate;
use Traversable;

/**
 * Class AuctionLotCustomListCsvReportFieldCollection
 * @package Sam\Report\Lot\AuctionLotCustomList\Media\Csv\ReportField
 */
class AuctionLotCustomListCsvReportFieldCollection extends CustomizableClass implements IteratorAggregate
{
    /**
     * @var AuctionLotCustomListCsvReportField[]
     */
    protected array $fields = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param CustomCsvExportData[] $fieldsConfig
     * @return static
     */
    public function fromFieldsConfig(array $fieldsConfig): static
    {
        $this->fields = array_map(
            static function (CustomCsvExportData $fieldConfig) {
                return AuctionLotCustomListCsvReportField::new()->construct($fieldConfig);
            },
            $fieldsConfig
        );
        return $this;
    }

    /**
     * @return array
     */
    public function getMapping(): array
    {
        $mappings = [];
        foreach ($this->fields as $field) {
            $mappings[] = $field->getMapping();
        }
        $mappedFields = array_merge(...$mappings);
        $mappedFields = array_unique($mappedFields);
        return $mappedFields;
    }

    /**
     * @return bool
     */
    public function hasCategoryField(): bool
    {
        return $this->inCollection(
            static function (AuctionLotCustomListCsvReportField $field) {
                return $field->isCategoryField();
            }
        );
    }

    /**
     * @return bool
     */
    public function hasLotImageField(): bool
    {
        return $this->inCollection(
            static function (AuctionLotCustomListCsvReportField $field) {
                return $field->isLotImageField();
            }
        );
    }

    /**
     * @return bool
     */
    public function hasConsignorField(): bool
    {
        return $this->inCollection(
            static function (AuctionLotCustomListCsvReportField $field) {
                return $field->isConsignorField();
            }
        );
    }

    /**
     * @return bool
     */
    public function hasLocationField(): bool
    {
        return $this->inCollection(
            static function (AuctionLotCustomListCsvReportField $field) {
                return $field->isLocationField();
            }
        );
    }

    /**
     * @return bool
     */
    public function hasCustomField(): bool
    {
        return $this->inCollection(
            static function (AuctionLotCustomListCsvReportField $field) {
                return $field->isCustomField();
            }
        );
    }

    /**
     * @param Closure $compareFunc
     * @return bool
     */
    private function inCollection(Closure $compareFunc): bool
    {
        foreach ($this->fields as $field) {
            if ($compareFunc($field) === true) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable|ArrayIterator
    {
        return new ArrayIterator($this->fields);
    }
}
