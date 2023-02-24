<?php
/**
 * SAM-9741: Admin options Inventory page - Add "Required" property for all fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Provider\Map;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotImportCsvFieldMap
 * @package Sam\Lot\LotFieldConfig\Provider\Map
 */
class LotImportCsvFieldMap extends CustomizableClass implements LotFieldMapInterface
{
    protected const FIELD_CONFIG_MAP = [
        Constants\Csv\Lot::LOT_CATEGORY => Constants\LotFieldConfig::CATEGORY,
        Constants\Csv\Lot::LOT_DESCRIPTION => Constants\LotFieldConfig::LOT_DESCRIPTION,
        Constants\Csv\Lot::LOT_NAME => Constants\LotFieldConfig::LOT_NAME,
        Constants\Csv\Lot::LOT_FULL_NUMBER => Constants\LotFieldConfig::LOT_NUMBER,
        Constants\Csv\Lot::LOT_NUM => Constants\LotFieldConfig::LOT_NUMBER,
        Constants\Csv\Lot::LOT_NUM_EXT => Constants\LotFieldConfig::LOT_NUMBER,
        Constants\Csv\Lot::LOT_NUM_PREFIX => Constants\LotFieldConfig::LOT_NUMBER,
        Constants\Csv\Lot::GROUP_ID => Constants\LotFieldConfig::LOT_GROUP,
        Constants\Csv\Lot::PUBLISH_DATE => Constants\LotFieldConfig::PUBLISH_DATE,
        Constants\Csv\Lot::UNPUBLISH_DATE => Constants\LotFieldConfig::UNPUBLISH_DATE,
        Constants\Csv\Lot::START_BIDDING_DATE => Constants\LotFieldConfig::START_BIDDING_DATE,
        Constants\Csv\Lot::END_PREBIDDING_DATE => Constants\LotFieldConfig::END_PREBIDDING_DATE,
        Constants\Csv\Lot::START_CLOSING_DATE => Constants\LotFieldConfig::START_CLOSING_DATE,
        Constants\Csv\Lot::SEO_URL => Constants\LotFieldConfig::SEO_URL,
        Constants\Csv\Lot::ITEM_NUM => Constants\LotFieldConfig::ITEM_NUMBER,
        Constants\Csv\Lot::ITEM_NUM_EXT => Constants\LotFieldConfig::ITEM_NUMBER,
        Constants\Csv\Lot::ITEM_FULL_NUMBER => Constants\LotFieldConfig::ITEM_NUMBER,
        Constants\Csv\Lot::QUANTITY => Constants\LotFieldConfig::QUANTITY,
        Constants\Csv\Lot::QUANTITY_X_MONEY => Constants\LotFieldConfig::QUANTITY,
        Constants\Csv\Lot::QUANTITY_DIGITS => Constants\LotFieldConfig::QUANTITY_DIGITS,
        Constants\Csv\Lot::CHANGES => Constants\LotFieldConfig::CHANGES,
        Constants\Csv\Lot::WARRANTY => Constants\LotFieldConfig::WARRANTY,
        Constants\Csv\Lot::TERMS_AND_CONDITIONS => Constants\LotFieldConfig::TERMS_AND_CONDITIONS,
        Constants\Csv\Lot::LOT_IMAGE => Constants\LotFieldConfig::LOT_IMAGE,
        Constants\Csv\Lot::LISTING_ONLY => Constants\LotFieldConfig::LISTING_ONLY,
        Constants\Csv\Lot::BUY_NOW_AMOUNT => Constants\LotFieldConfig::BUY_NOW,
        Constants\Csv\Lot::NO_BIDDING => Constants\LotFieldConfig::BUY_NOW,
        Constants\Csv\Lot::BEST_OFFER => Constants\LotFieldConfig::BUY_NOW,
        Constants\Csv\Lot::HIGH_ESTIMATE => Constants\LotFieldConfig::ESTIMATES,
        Constants\Csv\Lot::LOW_ESTIMATE => Constants\LotFieldConfig::ESTIMATES,
        Constants\Csv\Lot::STARTING_BID => Constants\LotFieldConfig::STARTING_BID,
        Constants\Csv\Lot::INCREMENT => Constants\LotFieldConfig::INCREMENTS,
        Constants\Csv\Lot::COST => Constants\LotFieldConfig::COST,
        Constants\Csv\Lot::REPLACEMENT_PRICE => Constants\LotFieldConfig::REPLACEMENT_PRICE,
        Constants\Csv\Lot::RESERVE_PRICE => Constants\LotFieldConfig::RESERVE_PRICE,
        Constants\Csv\Lot::CONSIGNOR => Constants\LotFieldConfig::CONSIGNOR,
        Constants\Csv\Lot::ONLY_TAX_BP => Constants\LotFieldConfig::ONLY_TAX_BP,
        Constants\Csv\Lot::SALES_TAX => Constants\LotFieldConfig::TAX,
        Constants\Csv\Lot::HP_TAX_SCHEMA_ID => Constants\LotFieldConfig::TAX_SCHEMA,
        Constants\Csv\Lot::BP_TAX_SCHEMA_ID => Constants\LotFieldConfig::TAX_SCHEMA,
        Constants\Csv\Lot::BP_SETTING => Constants\LotFieldConfig::BUYERS_PREMIUMS,
        Constants\Csv\Lot::BP_RANGE_CALCULATION => Constants\LotFieldConfig::BUYERS_PREMIUMS,
        Constants\Csv\Lot::ADDITIONAL_BP_INTERNET => Constants\LotFieldConfig::BUYERS_PREMIUMS,
        Constants\Csv\Lot::NO_TAX_OUTSIDE_STATE => Constants\LotFieldConfig::NO_TAX_OUTSIDE,
        Constants\Csv\Lot::RETURNED => Constants\LotFieldConfig::RETURNED,
        Constants\Csv\Lot::FEATURED => Constants\LotFieldConfig::FEATURED,
        Constants\Csv\Lot::NOTE_TO_CLERK => Constants\LotFieldConfig::CLERK_NOTE,
        Constants\Csv\Lot::GENERAL_NOTE => Constants\LotFieldConfig::GENERAL_NOTE,
        Constants\Csv\Lot::ITEM_TAX_COUNTRY => Constants\LotFieldConfig::ITEM_BILLING_COUNTRY,
        Constants\Csv\Lot::ITEM_TAX_STATES => Constants\LotFieldConfig::ITEM_BILLING_COUNTRY,
        Constants\Csv\Lot::BULK_CONTROL => Constants\LotFieldConfig::BULK_GROUP,
        Constants\Csv\Lot::BULK_WIN_BID_DISTRIBUTION => Constants\LotFieldConfig::BULK_GROUP,
        Constants\Csv\Lot::LOCATION => Constants\LotFieldConfig::LOCATION,
        Constants\Csv\Lot::LOCATION_ADDRESS => Constants\LotFieldConfig::LOCATION,
        Constants\Csv\Lot::LOCATION_COUNTRY => Constants\LotFieldConfig::LOCATION,
        Constants\Csv\Lot::LOCATION_COUNTY => Constants\LotFieldConfig::LOCATION,
        Constants\Csv\Lot::LOCATION_CITY => Constants\LotFieldConfig::LOCATION,
        Constants\Csv\Lot::LOCATION_STATE => Constants\LotFieldConfig::LOCATION,
        Constants\Csv\Lot::LOCATION_ZIP => Constants\LotFieldConfig::LOCATION,
        Constants\Csv\Lot::LOCATION_LOGO => Constants\LotFieldConfig::LOCATION,
        Constants\Csv\Lot::SEO_META_TITLE => Constants\LotFieldConfig::SEO_OPTIONS,
        Constants\Csv\Lot::SEO_META_KEYWORDS => Constants\LotFieldConfig::SEO_OPTIONS,
        Constants\Csv\Lot::SEO_META_DESCRIPTION => Constants\LotFieldConfig::SEO_OPTIONS,
        Constants\Csv\Lot::CONSIGNOR_COMMISSION_CALCULATION_METHOD => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        Constants\Csv\Lot::CONSIGNOR_COMMISSION_ID => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        Constants\Csv\Lot::CONSIGNOR_COMMISSION_RANGES => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_ID => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_RANGES => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_REFERENCE => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_ID => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_RANGES => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_REFERENCE => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        Constants\Csv\Lot::FB_OG_TITLE => Constants\LotFieldConfig::FACEBOOK_OPENGRAPH,
        Constants\Csv\Lot::FB_OG_IMAGE_URL => Constants\LotFieldConfig::FACEBOOK_OPENGRAPH,
        Constants\Csv\Lot::FB_OG_DESCRIPTION => Constants\LotFieldConfig::FACEBOOK_OPENGRAPH,
    ];

    protected const ALWAYS_OPTIONAL_FIELDS = [
        Constants\Csv\Lot::LOT_NUM_EXT,
        Constants\Csv\Lot::LOT_NUM_PREFIX,
        Constants\Csv\Lot::ITEM_NUM_EXT,
        Constants\Csv\Lot::NO_TAX_OUTSIDE_STATE,
        Constants\Csv\Lot::QUANTITY_X_MONEY
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
        return self::FIELD_CONFIG_MAP[$field] ?? null;
    }

    public function isAlwaysOptionalField(string $field): bool
    {
        return in_array($field, self::ALWAYS_OPTIONAL_FIELDS, true);
    }
}
