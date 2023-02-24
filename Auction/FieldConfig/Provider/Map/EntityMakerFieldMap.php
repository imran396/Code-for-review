<?php
/**
 * SAM-9688: Ability to make Auction and Lot/Item fields required to have values: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\FieldConfig\Provider\Map;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Responsible for mapping entity maker field to the field config index
 *
 * Class EntityMakerFieldMap
 * @package Sam\Auction\FieldConfig\Provider\Map
 */
class EntityMakerFieldMap extends CustomizableClass implements AuctionFieldMapInterface
{
    protected const FIELD_MAP = [
        'auctionInfoLink' => Constants\AuctionFieldConfig::AUCTION_INFO_LINK,
        'auctionType' => Constants\AuctionFieldConfig::AUCTION_TYPE,
        'biddingConsoleAccessDate' => Constants\AuctionFieldConfig::BIDDING_CONSOLE_ACCESS_DATE,
        'bpTaxSchemaId' => Constants\AuctionFieldConfig::TAX_SCHEMA,
        'clerkingStyle' => Constants\AuctionFieldConfig::CLERKING_STYLE,
        'clerkingStyleId' => Constants\AuctionFieldConfig::CLERKING_STYLE,
        'consignorCommissionCalculationMethod' => Constants\AuctionFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorCommissionId' => Constants\AuctionFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorCommissionRanges' => Constants\AuctionFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorSoldFeeCalculationMethod' => Constants\AuctionFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorSoldFeeId' => Constants\AuctionFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorSoldFeeRanges' => Constants\AuctionFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorSoldFeeReference' => Constants\AuctionFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorUnsoldFeeCalculationMethod' => Constants\AuctionFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorUnsoldFeeId' => Constants\AuctionFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorUnsoldFeeRanges' => Constants\AuctionFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'consignorUnsoldFeeReference' => Constants\AuctionFieldConfig::CONSIGNOR_COMMISSION_FEE,
        'dateAssignmentStrategy' => Constants\AuctionFieldConfig::DATE_ASSIGNMENT_MODE,
        'description' => Constants\AuctionFieldConfig::DESCRIPTION,
        'endPrebiddingDate' => Constants\AuctionFieldConfig::END_PREBIDDING_DATE,
        'endRegisterDate' => Constants\AuctionFieldConfig::END_REGISTER_DATE,
        'eventLocation' => Constants\AuctionFieldConfig::EVENT_LOCATION,
        'eventLocationId' => Constants\AuctionFieldConfig::EVENT_LOCATION,
        'eventType' => Constants\AuctionFieldConfig::EVENT_TYPE,
        'eventTypeId' => Constants\AuctionFieldConfig::EVENT_TYPE,
        'excludeClosedLots' => Constants\AuctionFieldConfig::EXCLUDE_CLOSED_LOTS,
        'hideUnsoldLots' => Constants\AuctionFieldConfig::LOT_VISIBILITY_OPTIONS,
        'hpTaxSchemaId' => Constants\AuctionFieldConfig::TAX_SCHEMA,
        'image' => Constants\AuctionFieldConfig::IMAGE,
        'invoiceLocation' => Constants\AuctionFieldConfig::INVOICE_LOCATION,
        'invoiceLocationId' => Constants\AuctionFieldConfig::INVOICE_LOCATION,
        'invoiceNotes' => Constants\AuctionFieldConfig::INVOICE_NOTES,
        'listingOnly' => Constants\AuctionFieldConfig::LISTING_ONLY,
        'liveEndDate' => Constants\AuctionFieldConfig::END_DATE,
        'lotsPerInterval' => Constants\AuctionFieldConfig::STAGGERED_CLOSING,
        'name' => Constants\AuctionFieldConfig::NAME,
        'notShowUpcomingLots' => Constants\AuctionFieldConfig::LOT_VISIBILITY_OPTIONS,
        'onlyOngoingLots' => Constants\AuctionFieldConfig::LOT_VISIBILITY_OPTIONS,
        'parcelChoice' => Constants\AuctionFieldConfig::PARCEL_CHOICE,
        'publishDate' => Constants\AuctionFieldConfig::PUBLISH_DATE,
        'reverse' => Constants\AuctionFieldConfig::REVERSE_AUCTION,
        'saleFullNo' => Constants\AuctionFieldConfig::SALE_NUM,
        'saleNum' => Constants\AuctionFieldConfig::SALE_NUM,
        'saleNumExt' => Constants\AuctionFieldConfig::SALE_NUM,
        'seoMetaDescription' => Constants\AuctionFieldConfig::SEO_OPTIONS,
        'seoMetaKeywords' => Constants\AuctionFieldConfig::SEO_OPTIONS,
        'seoMetaTitle' => Constants\AuctionFieldConfig::SEO_OPTIONS,
        'servicesTaxSchemaId' => Constants\AuctionFieldConfig::TAX_SCHEMA,
        'shippingInfo' => Constants\AuctionFieldConfig::SHIPPING_INFO,
        'staggerClosing' => Constants\AuctionFieldConfig::STAGGERED_CLOSING,
        'startBiddingDate' => Constants\AuctionFieldConfig::START_BIDDING_DATE,
        'startClosingDate' => Constants\AuctionFieldConfig::START_CLOSING_DATE,
        'startRegisterDate' => Constants\AuctionFieldConfig::START_REGISTER_DATE,
        'streamDisplay' => Constants\AuctionFieldConfig::STREAM_DISPLAY,
        'streamDisplayValue' => Constants\AuctionFieldConfig::STREAM_DISPLAY,
        'taxDefaultCountry' => Constants\AuctionFieldConfig::TAX_COUNTRY,
        'taxPercent' => Constants\AuctionFieldConfig::TAX,
        'termsAndConditions' => Constants\AuctionFieldConfig::TERMS_AND_CONDITIONS,
        'timezone' => Constants\AuctionFieldConfig::TIMEZONE,
        'unpublishDate' => Constants\AuctionFieldConfig::UNPUBLISH_DATE,
    ];

    protected const ALWAYS_OPTIONAL_FIELDS = [
        'saleNumExt',
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
        return self::FIELD_MAP[$field] ?? null;
    }

    public function isAlwaysOptionalField(string $field): bool
    {
        return in_array($field, self::ALWAYS_OPTIONAL_FIELDS, true);
    }
}
