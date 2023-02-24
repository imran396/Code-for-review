<?php

namespace Sam\Core\Constants;

/**
 * Class LotCloner
 * @package Sam\Core\Constants
 */
class LotCloner
{
    public const LC_CATEGORY = 'Category';
    public const LC_NAME = 'Name';
    public const LC_DESCRIPTION = 'Description';
    public const LC_CHANGES = 'Changes';
    public const LC_WARRANTY = 'Warranty';
    public const LC_IMAGES = 'Images';
    public const LC_IMAGE_DEFAULT = 'DefaultImage';
    public const LC_LOW_ESTIMATE = 'LowEstimate';
    public const LC_HIGH_ESTIMATE = 'HighEstimate';
    public const LC_STARTING_BID = 'StartingBid';
    public const LC_INCREMENTS = 'Increments';
    public const LC_COST = 'Cost';
    public const LC_REPLACEMENT_PRICE = 'ReplacementPrice';
    public const LC_RESERVE_PRICE = 'ReservePrice';
    public const LC_CONSIGNOR_ID = 'ConsignorId';
    public const LC_ONLY_TAX_BP = 'OnlyTaxBp';
    public const LC_SALES_TAX = 'SalesTax';
    public const LC_BUYERS_PREMIUM = 'BuyersPremium';
    public const LC_NO_TAX_OOS = 'NoTaxOos';
    public const LC_RETURNED = 'Returned';
    public const LC_TAX_DEFAULT_COUNTRY = 'TaxDefaultCountry';
    public const LC_LOCATION_ID = 'LocationId';
    public const LC_TAX_EXEMPT = 'TaxExempt';
    public const LC_GENERAL_NOTE = 'GeneralNote';
    public const LC_NOTE_TO_CLERK = 'NoteToClerk';

    public const LC_FULL_LOT_ITEM = 'FullLotItem';
    public const LC_FULL_AUCTION_LOT = 'FullAuctionLot';

    /** @var string[] */
    public static array $fieldNames = [
        self::LC_CATEGORY => 'Category',
        self::LC_NAME => 'Name',
        self::LC_DESCRIPTION => 'Description',
        self::LC_CHANGES => 'Changes',
        self::LC_WARRANTY => 'Warranty',
        self::LC_IMAGES => 'Images',
        self::LC_LOW_ESTIMATE => 'Low Estimate',
        self::LC_HIGH_ESTIMATE => 'High Estimate',
        self::LC_STARTING_BID => 'Starting Bid',
        self::LC_INCREMENTS => 'Increments',
        self::LC_COST => 'Cost',
        self::LC_REPLACEMENT_PRICE => 'Replacement Price',
        self::LC_RESERVE_PRICE => 'Reserve Price',
        self::LC_CONSIGNOR_ID => 'Consignor',
        self::LC_ONLY_TAX_BP => 'Only Tax BP',
        self::LC_SALES_TAX => 'Sales Tax',
        self::LC_BUYERS_PREMIUM => 'Buyers Premium',
        self::LC_NO_TAX_OOS => 'No Tax on sales outside of state',
        self::LC_RETURNED => 'Returned',
        self::LC_TAX_DEFAULT_COUNTRY => 'Item tax country',
        self::LC_LOCATION_ID => 'Location',
        self::LC_TAX_EXEMPT => 'TaxExempt',
        self::LC_GENERAL_NOTE => 'General note',
        self::LC_NOTE_TO_CLERK => 'Note to auction clerk',
    ];
}
