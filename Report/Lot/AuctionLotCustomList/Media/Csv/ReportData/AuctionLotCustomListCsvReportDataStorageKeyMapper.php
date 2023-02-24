<?php
/**
 * SAM-4644: Refactor "Custom CSV Export" report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug. 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\AuctionLotCustomList\Media\Csv\ReportData;

use RuntimeException;
use Sam\Core\Service\CustomizableClass;
use Sam\Report\Lot\AuctionLotCustomList\Media\Csv\ReportField\AuctionLotCustomListCsvReportField as ReportField;

/**
 * Class AuctionLotCustomListCsvReportDtoStorageKeyMapper
 * @package Sam\Report\Lot\AuctionLotCustomList\Media\Csv\Dto
 */
class AuctionLotCustomListCsvReportDataStorageKeyMapper extends CustomizableClass
{
    public const CATEGORIES_STORAGE_KEY = 'Categories';
    public const LOT_IMAGES_STORAGE_KEY = 'LotImages';
    public const CONSIGNOR_STORAGE_KEY = 'Consignor';
    public const LOCATION_STORAGE_KEY = 'Location';
    public const CUSTOM_FIELD_STORAGE_KEY_TEMPLATE = 'CustomData_%d';
    public const QUANTITY_STORAGE_KEYS = ['quantity', 'quantity_scale'];

    protected array $mappingCache = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param ReportField $reportField
     * @return array
     */
    public function getStorageKeys(ReportField $reportField): array
    {
        $reportFieldKey = $reportField->getKey();
        if (!array_key_exists($reportFieldKey, $this->mappingCache)) {
            $this->mappingCache[$reportFieldKey] = $this->detectStorageKeyMapping($reportField);
        }
        return $this->mappingCache[$reportFieldKey];
    }

    /**
     * @param ReportField $reportField
     * @return array
     */
    private function detectStorageKeyMapping(ReportField $reportField): array
    {
        $storageKeys = [];
        if ($reportField->isCategoryField()) {
            $storageKeys[] = self::CATEGORIES_STORAGE_KEY;
        } elseif ($reportField->isLotImageField()) {
            $storageKeys[] = self::LOT_IMAGES_STORAGE_KEY;
        } elseif ($reportField->isConsignorField()) {
            $storageKeys[] = self::CONSIGNOR_STORAGE_KEY;
        } elseif ($reportField->isLocationField()) {
            $storageKeys[] = self::LOCATION_STORAGE_KEY;
        } elseif ($reportField->isCustomField()) {
            $customFieldId = $reportField->getCustomFieldId();
            $storageKeys[] = sprintf(self::CUSTOM_FIELD_STORAGE_KEY_TEMPLATE, $customFieldId);
        } elseif ($reportField->isQuantityField()) {
            $storageKeys = self::QUANTITY_STORAGE_KEYS;
        } else {
            $storageKeys = $reportField->getMapping();
            if (!$storageKeys) {
                throw new RuntimeException(
                    sprintf(
                        'Mapping for Field %s with key %s not found',
                        $reportField->getName(),
                        $reportField->getKey()
                    )
                );
            }
            $storageKeys = array_map([$this, 'convertDbFieldMappingToArrayKey'], $storageKeys);
        }
        return $storageKeys;
    }

    /**
     * @param string $dbFieldMapping
     * @return string
     */
    private function convertDbFieldMappingToArrayKey(string $dbFieldMapping): string
    {
        if (str_contains($dbFieldMapping, '.')) {
            [, $key] = explode('.', $dbFieldMapping);
        } else {
            $key = $dbFieldMapping;
        }
        return trim($key, '`');
    }
}
