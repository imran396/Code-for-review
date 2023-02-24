<?php
/**
 * SAM-9741: Admin options Inventory page - Add "Required" property for all fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Provider\Map;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotClonerFieldMap
 * @package Sam\Lot\LotFieldConfig\Provider\Map
 */
class LotClonerFieldMap extends CustomizableClass implements LotFieldMapInterface
{
    protected const FIELD_CONFIG_MAP = [
        Constants\LotCloner::LC_CONSIGNOR_ID => Constants\LotFieldConfig::CONSIGNOR,
        Constants\LotCloner::LC_DESCRIPTION => Constants\LotFieldConfig::LOT_DESCRIPTION,
        Constants\LotCloner::LC_LOW_ESTIMATE => Constants\LotFieldConfig::ESTIMATES,
        Constants\LotCloner::LC_HIGH_ESTIMATE => Constants\LotFieldConfig::ESTIMATES,
        Constants\LotCloner::LC_NAME => Constants\LotFieldConfig::LOT_NAME,
        Constants\LotCloner::LC_NOTE_TO_CLERK => Constants\LotFieldConfig::CLERK_NOTE,
        Constants\LotCloner::LC_SALES_TAX => Constants\LotFieldConfig::TAX,
        Constants\LotCloner::LC_CATEGORY => Constants\LotFieldConfig::CATEGORY,
        Constants\LotCloner::LC_CHANGES => Constants\LotFieldConfig::CHANGES,
        Constants\LotCloner::LC_WARRANTY => Constants\LotFieldConfig::WARRANTY,
        Constants\LotCloner::LC_IMAGES => Constants\LotFieldConfig::LOT_IMAGE,
        Constants\LotCloner::LC_STARTING_BID => Constants\LotFieldConfig::STARTING_BID,
        Constants\LotCloner::LC_INCREMENTS => Constants\LotFieldConfig::INCREMENTS,
        Constants\LotCloner::LC_COST => Constants\LotFieldConfig::COST,
        Constants\LotCloner::LC_REPLACEMENT_PRICE => Constants\LotFieldConfig::REPLACEMENT_PRICE,
        Constants\LotCloner::LC_RESERVE_PRICE => Constants\LotFieldConfig::RESERVE_PRICE,
        Constants\LotCloner::LC_ONLY_TAX_BP => Constants\LotFieldConfig::ONLY_TAX_BP,
        Constants\LotCloner::LC_BUYERS_PREMIUM => Constants\LotFieldConfig::BUYERS_PREMIUMS,
        Constants\LotCloner::LC_NO_TAX_OOS => Constants\LotFieldConfig::NO_TAX_OUTSIDE,
        Constants\LotCloner::LC_RETURNED => Constants\LotFieldConfig::RETURNED,
        Constants\LotCloner::LC_TAX_DEFAULT_COUNTRY => Constants\LotFieldConfig::ITEM_BILLING_COUNTRY,
        Constants\LotCloner::LC_LOCATION_ID => Constants\LotFieldConfig::LOCATION,
        Constants\LotCloner::LC_GENERAL_NOTE => Constants\LotFieldConfig::GENERAL_NOTE,
    ];

    protected const ALWAYS_OPTIONAL_FIELDS = [
        Constants\LotCloner::LC_NO_TAX_OOS,
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getFieldConfigIndex(string $field): ?string
    {
        return self::FIELD_CONFIG_MAP[$field] ?? $field;
    }

    public function isAlwaysOptionalField(string $field): bool
    {
        return in_array($field, self::ALWAYS_OPTIONAL_FIELDS, true);
    }
}
