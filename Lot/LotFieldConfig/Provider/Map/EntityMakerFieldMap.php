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
 * Class EntityMakerFieldMap
 * @package Sam\Lot\LotFieldConfig\Provider\Map
 */
class EntityMakerFieldMap extends CustomizableClass implements LotFieldMapInterface
{
    protected const FIELD_CONFIG_MAP = [
        'additionalBpInternet' => Constants\LotFieldConfig::BUYERS_PREMIUMS,
        'auctionSoldId' => Constants\LotFieldConfig::WINNING_BIDDER,
        'auctionSoldName' => Constants\LotFieldConfig::WINNING_BIDDER,
        'bestOffer' => Constants\LotFieldConfig::BUY_NOW,
        'bpRangeCalculation' => Constants\LotFieldConfig::BUYERS_PREMIUMS,
        'bpRule' => Constants\LotFieldConfig::BUYERS_PREMIUMS,
        'bpTaxSchemaId' => Constants\LotFieldConfig::TAX_SCHEMA,
        'bulkControl' => Constants\LotFieldConfig::BULK_GROUP,
        'bulkWinBidDistribution' => Constants\LotFieldConfig::BULK_GROUP,
        'buyNowAmount' => Constants\LotFieldConfig::BUY_NOW,
        'buyNowSelectQuantityEnabled' => Constants\LotFieldConfig::BUY_NOW,
        'buyersPremiumDataRows' => Constants\LotFieldConfig::BUYERS_PREMIUMS,
        'buyersPremiumString' => Constants\LotFieldConfig::BUYERS_PREMIUMS,
        'buyersPremiums' => Constants\LotFieldConfig::BUYERS_PREMIUMS,
        'categories' => Constants\LotFieldConfig::CATEGORY,
        'categoriesIds' => Constants\LotFieldConfig::CATEGORY,
        'categoriesNames' => Constants\LotFieldConfig::CATEGORY,
        'changes' => Constants\LotFieldConfig::CHANGES,
        'consignorCommissionCalculationMethod' => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorCommissionId' => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorCommissionRanges' => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorId' => Constants\LotFieldConfig::CONSIGNOR,
        'consignorName' => Constants\LotFieldConfig::CONSIGNOR,
        'consignorSoldFeeCalculationMethod' => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorSoldFeeId' => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorSoldFeeRanges' => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorSoldFeeReference' => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorUnsoldFeeCalculationMethod' => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorUnsoldFeeId' => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorUnsoldFeeRanges' => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorUnsoldFeeReference' => Constants\LotFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'cost' => Constants\LotFieldConfig::COST,
        'dateSold' => Constants\LotFieldConfig::WINNING_BIDDER,
        'description' => Constants\LotFieldConfig::LOT_DESCRIPTION,
        'endPrebiddingDate' => Constants\LotFieldConfig::END_PREBIDDING_DATE,
        'fbOgDescription' => Constants\LotFieldConfig::FACEBOOK_OPENGRAPH,
        'fbOgImageUrl' => Constants\LotFieldConfig::FACEBOOK_OPENGRAPH,
        'fbOgTitle' => Constants\LotFieldConfig::FACEBOOK_OPENGRAPH,
        'featured' => Constants\LotFieldConfig::FEATURED,
        'generalNote' => Constants\LotFieldConfig::GENERAL_NOTE,
        'hammerPrice' => Constants\LotFieldConfig::WINNING_BIDDER,
        'highEstimate' => Constants\LotFieldConfig::ESTIMATES,
        'hpTaxSchemaId' => Constants\LotFieldConfig::TAX_SCHEMA,
        'images' => Constants\LotFieldConfig::LOT_IMAGE,
        'increments' => Constants\LotFieldConfig::INCREMENTS,
        'itemFullNum' => Constants\LotFieldConfig::ITEM_NUMBER,
        'itemNum' => Constants\LotFieldConfig::ITEM_NUMBER,
        'itemNumExt' => Constants\LotFieldConfig::ITEM_NUMBER,
        'listingOnly' => Constants\LotFieldConfig::LISTING_ONLY,
        'location' => Constants\LotFieldConfig::LOCATION,
        'lotFullNum' => Constants\LotFieldConfig::LOT_NUMBER,
        'lotGroup' => Constants\LotFieldConfig::LOT_GROUP,
        'lotItemTaxArr' => Constants\LotFieldConfig::TAX_ARTIST_RESALE_RIGHTS,
        'lotNum' => Constants\LotFieldConfig::LOT_NUMBER,
        'lotNumExt' => Constants\LotFieldConfig::LOT_NUMBER,
        'lotNumPrefix' => Constants\LotFieldConfig::LOT_NUMBER,
        'lotStatus' => Constants\LotFieldConfig::LOT_STATUS,
        'lotStatusId' => Constants\LotFieldConfig::LOT_STATUS,
        'lowEstimate' => Constants\LotFieldConfig::ESTIMATES,
        'name' => Constants\LotFieldConfig::LOT_NAME,
        'noBidding' => Constants\LotFieldConfig::BUY_NOW,
        'noTaxOos' => Constants\LotFieldConfig::NO_TAX_OUTSIDE,
        'noteToClerk' => Constants\LotFieldConfig::CLERK_NOTE,
        'onlyTaxBp' => Constants\LotFieldConfig::ONLY_TAX_BP,
        'publishDate' => Constants\LotFieldConfig::PUBLISH_DATE,
        'quantity' => Constants\LotFieldConfig::QUANTITY,
        'quantityDigits' => Constants\LotFieldConfig::QUANTITY_DIGITS,
        'quantityXMoney' => Constants\LotFieldConfig::QUANTITY,
        'replacementPrice' => Constants\LotFieldConfig::REPLACEMENT_PRICE,
        'reservePrice' => Constants\LotFieldConfig::RESERVE_PRICE,
        'returned' => Constants\LotFieldConfig::RETURNED,
        'salesTax' => Constants\LotFieldConfig::TAX,
        'seoMetaDescription' => Constants\LotFieldConfig::SEO_OPTIONS,
        'seoMetaKeywords' => Constants\LotFieldConfig::SEO_OPTIONS,
        'seoMetaTitle' => Constants\LotFieldConfig::SEO_OPTIONS,
        'seoUrl' => Constants\LotFieldConfig::SEO_URL,
        'startBiddingDate' => Constants\LotFieldConfig::START_BIDDING_DATE,
        'startClosingDate' => Constants\LotFieldConfig::START_CLOSING_DATE,
        'startingBid' => Constants\LotFieldConfig::STARTING_BID,
        'taxDefaultCountry' => Constants\LotFieldConfig::ITEM_BILLING_COUNTRY,
        'termsAndConditions' => Constants\LotFieldConfig::TERMS_AND_CONDITIONS,
        'timezone' => Constants\LotFieldConfig::TIMEZONE,
        'unpublishDate' => Constants\LotFieldConfig::UNPUBLISH_DATE,
        'warranty' => Constants\LotFieldConfig::WARRANTY,
        'winningBidderId' => Constants\LotFieldConfig::WINNING_BIDDER,
        'winningBidderName' => Constants\LotFieldConfig::WINNING_BIDDER,
    ];

    protected const ALWAYS_OPTIONAL_FIELDS = [
        'lotNumExt',
        'lotNumPrefix',
        'itemNumExt',
        'noTaxOos',
        'quantityXMoney',
        'bestOffer',
        'noBidding',
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
