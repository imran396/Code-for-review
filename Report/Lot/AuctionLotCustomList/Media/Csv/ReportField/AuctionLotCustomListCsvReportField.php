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

use CustomCsvExportData;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionLotCustomListCsvReportField
 * @package Sam\Report\Lot\AuctionLotCustomList\Media\Csv\ReportField
 */
class AuctionLotCustomListCsvReportField extends CustomizableClass
{
    private const IMAGE_FIELD = 'f12';
    private const CATEGORY_FIELD = 'f6';
    private const CONSIGNOR_FIELD = 'f20';
    private const LOCATION_FIELD = 'f35';
    private const TIMED_START_DATE_FIELD = 'f7';
    private const TIMED_END_DATE_FIELD = 'f8';
    private const LOT_NAME_FIELD = 'f9';
    private const LOT_CUSTOM_FIELD_PREFIX = 'fc';
    private const ITEM_NUMBER_CONCATENATED_FIELD = 'f42';
    private const LOT_NUMBER_CONCATENATED_FIELD = 'f43';
    private const LOT_NUMBER_FIELD = 'f1';
    private const ITEM_NUMBER_FIELD = 'f3';
    private const GROUP_ID_FIELD = 'f5';
    private const ITEM_ID_FIELD = 'f37';
    private const AUCTION_ID_FIELD = 'f38';
    private const QUANTITY_FIELD = 'f30';

    private const FIELDS_MAPPING = [
        self::LOT_NAME_FIELD => 'name',
        self::TIMED_START_DATE_FIELD => [
            'lot_status_id',
            'start_date',
            'timezone_id',
        ],
        self::TIMED_END_DATE_FIELD => [
            'lot_status_id',
            'end_date',
            '`order`',
            'timezone_id',
        ],
        self::ITEM_NUMBER_CONCATENATED_FIELD => [
            'item_num',
            'item_num_ext'
        ],
        self::LOT_NUMBER_CONCATENATED_FIELD => [
            'lot_num',
            'lot_num_prefix',
            'lot_num_ext'
        ],
        self::QUANTITY_FIELD => [
            'ali.quantity',
            'COALESCE(
                ali.quantity_digits, 
                li.quantity_digits, 
                (SELECT lc.quantity_digits
                 FROM lot_category lc
                   INNER JOIN lot_item_category lic ON lc.id = lic.lot_category_id
                 WHERE lic.lot_item_id = li.id
                   AND lc.active = 1
                 ORDER BY lic.id
                 LIMIT 1), 
                (SELECT seta.quantity_digits FROM setting_auction seta WHERE seta.account_id = li.account_id)
            ) as quantity_scale',
        ],
        'f1' => 'item_num',
        'f41' => 'item_num_ext',
        'f2' => 'lot_num_prefix',
        'f3' => 'lot_num',
        'f4' => 'lot_num_ext',
        'f5' => 'group_id',
        'f25' => 'buy_now_amount',
        'f22' => 'buy_offer',
        'f24' => 'best_offer',
        'f26' => 'no_bidding',
        'f10' => 'description',
        'f11' => 'warranty',
        'f13' => 'low_estimate',
        'f14' => 'high_estimate',
        'f39' => 'listing_only',
        'f15' => 'starting_bid',
        'f16' => 'cost',
        'f17' => 'replacement_price',
        'f18' => 'reserve_price',
        'f27' => 'sales_tax',
        'f28' => 'no_tax_oos',
        'f29' => 'sample_lot',
        'f31' => 'ali.quantity_x_money',
        'f32' => 'terms_and_conditions',
        'f33' => 'changes',
        'f34' => 'only_tax_bp',
        'f36' => '`order`',
        'f37' => 'ali.lot_item_id',
        'f38' => 'li.auction_id',
    ];

    protected CustomCsvExportData $fieldConfig;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param CustomCsvExportData $fieldConfig
     * @return static
     */
    public function construct(CustomCsvExportData $fieldConfig): static
    {
        $this->fieldConfig = $fieldConfig;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->fieldConfig->FieldName;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->fieldConfig->FieldIndex;
    }

    /**
     * @return array
     */
    public function getMapping(): array
    {
        $mapping = [];
        if ($this->isConsignorField()) {
            $mapping = ['consignor_id'];
        } elseif ($this->isLocationField()) {
            $mapping = ['location_id'];
        } else {
            $key = $this->getKey();
            if (array_key_exists($key, self::FIELDS_MAPPING)) {
                $mapping = (array)self::FIELDS_MAPPING[$key];
            }
        }

        return $mapping;
    }

    /**
     * @return bool
     */
    public function isCategoryField(): bool
    {
        return $this->getKey() === self::CATEGORY_FIELD;
    }

    /**
     * @return bool
     */
    public function isLotImageField(): bool
    {
        return $this->getKey() === self::IMAGE_FIELD;
    }

    /**
     * @return bool
     */
    public function isConsignorField(): bool
    {
        return $this->getKey() === self::CONSIGNOR_FIELD;
    }

    /**
     * @return bool
     */
    public function isLocationField(): bool
    {
        return $this->getKey() === self::LOCATION_FIELD;
    }

    /**
     * @return bool
     */
    public function isCustomField(): bool
    {
        return str_starts_with($this->getKey(), self::LOT_CUSTOM_FIELD_PREFIX);
    }

    /**
     * @return bool
     */
    public function isStartDateField(): bool
    {
        return $this->getKey() === self::TIMED_START_DATE_FIELD;
    }

    /**
     * @return bool
     */
    public function isEndDateField(): bool
    {
        return $this->getKey() === self::TIMED_END_DATE_FIELD;
    }

    /**
     * @return bool
     */
    public function isLotNameField(): bool
    {
        return $this->getKey() === self::LOT_NAME_FIELD;
    }

    /**
     * @return bool
     */
    public function isLotNumberField(): bool
    {
        return $this->getKey() === self::LOT_NUMBER_FIELD;
    }

    /**
     * @return bool
     */
    public function isItemNumberField(): bool
    {
        return $this->getKey() === self::ITEM_NUMBER_FIELD;
    }

    /**
     * @return bool
     */
    public function isItemNumberConcatenatedField(): bool
    {
        return $this->getKey() === self::ITEM_NUMBER_CONCATENATED_FIELD;
    }

    /**
     * @return bool
     */
    public function isLotNumberConcatenatedField(): bool
    {
        return $this->getKey() === self::LOT_NUMBER_CONCATENATED_FIELD;
    }

    /**
     * @return bool
     */
    public function isGroupIdField(): bool
    {
        return $this->getKey() === self::GROUP_ID_FIELD;
    }

    /**
     * @return bool
     */
    public function isItemIdField(): bool
    {
        return $this->getKey() === self::ITEM_ID_FIELD;
    }

    /**
     * @return bool
     */
    public function isAuctionIdField(): bool
    {
        return $this->getKey() === self::AUCTION_ID_FIELD;
    }

    public function isQuantityField(): bool
    {
        return $this->getKey() === self::QUANTITY_FIELD;
    }

    /**
     * @return int
     */
    public function getCustomFieldId(): int
    {
        return (int)str_replace(self::LOT_CUSTOM_FIELD_PREFIX, '', $this->getKey());
    }
}
