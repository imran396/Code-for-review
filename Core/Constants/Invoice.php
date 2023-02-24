<?php

namespace Sam\Core\Constants;

/**
 * Class Invoice
 * @package Sam\Core\Constants
 */
class Invoice
{
    // Invoice Status
    public const IS_OPEN = 1;
    public const IS_PENDING = 2;
    public const IS_PAID = 3;
    public const IS_SHIPPED = 4;
    public const IS_CANCELED = 5;
    public const IS_DELETED = 6;

    /** @var int[] */
    public static array $invoiceStatuses = [
        self::IS_OPEN,
        self::IS_PENDING,
        self::IS_PAID,
        self::IS_SHIPPED,
        self::IS_CANCELED,
        self::IS_DELETED,
    ];

    /** @var string[] */
    public static array $invoiceStatusNames = [
        self::IS_OPEN => 'Open',
        self::IS_PENDING => 'Pending',
        self::IS_PAID => 'Paid',
        self::IS_SHIPPED => 'Shipped',
        self::IS_CANCELED => 'Canceled',
        self::IS_DELETED => 'Deleted',
    ];

    /** @var int[] */
    public static array $availableInvoiceStatuses = [
        self::IS_OPEN,
        self::IS_PENDING,
        self::IS_PAID,
        self::IS_SHIPPED,
        self::IS_CANCELED,
    ];

    /** @var int[] */
    public static array $openInvoiceStatuses = [
        self::IS_OPEN,
        self::IS_PENDING,
        self::IS_PAID,
        self::IS_SHIPPED,
    ];

    /** @var int[] */
    public static array $publicAvailableInvoiceStatuses = [
        self::IS_PENDING,
        self::IS_PAID,
        self::IS_SHIPPED,
    ];

    // InvoiceLineItem.AuctionType = 'All'
    public const ILIAT_ALL = 'A';
    /** @var string[] */
    public static array $invoiceLineItemAuctionTypes = [self::ILIAT_ALL, Auction::TIMED, Auction::LIVE, Auction::HYBRID];

    // invoice_item.break_down values
    public const BD_CONSOLIDATED = 'C';
    public const BD_ITEM_BY_ITEM = 'I';
    public const BD_DEFAULT = self::BD_CONSOLIDATED;
    /** @var string[] */
    public static array $breakDowns = [
        self::BD_CONSOLIDATED => 'Consolidated',
        self::BD_ITEM_BY_ITEM => 'Item by Item',
    ];

    // Auto-invoicing cases (SAM-5467)
    public const AIC_REGULAR = 'REGULAR'; // Regular bidding items will be auto invoice
    public const AIC_BUY_NOW = 'BUY_NOW'; // Buy now items will be auto invoice
    /** @var string[] */
    public const AUTO_INVOICING_CASES = [self::AIC_REGULAR, self::AIC_BUY_NOW];
    public const AUCTION_INVOICE_PAGE = 'auction-invoice';
    public const MANAGE_INVOICE_PAGE = 'manage-invoice';

    // Tax Designation Strategy
    public const TDS_LEGACY = 1;
    public const TDS_STACKED_TAX = 2;
    public const TAX_DESIGNATION_STRATEGIES = [self::TDS_LEGACY, self::TDS_STACKED_TAX];

    // Invoice Additional
    public const IA_EXTRA_CHARGE = 1;
    public const IA_EXTRA_FEE = 2; // Charges are built on the base of the "Invoice Line Item" feature
    public const IA_SHIPPING = 3;
    public const IA_ARTIST_RESALE_RIGHTS = 4;
    public const IA_PROCESSING_FEE = 5;
    public const IA_CC_SURCHARGE = 6;
    public const IA_CASH_DISCOUNT = 7;
    // Will delete next?
    public const IA_COUPON_FREE_SHIPPING = 7;
    public const IA_COUPON_FIXED_AMOUNT = 8;
    public const IA_COUPON_PERCENTAGE = 9;

    public static array $invoiceAdditionalTypeNames = [
        self::IA_EXTRA_CHARGE => 'Extra Charge',
        self::IA_EXTRA_FEE => 'Extra Fee',
        self::IA_SHIPPING => 'Shipping & Handling',
        self::IA_ARTIST_RESALE_RIGHTS => 'Artist Resale Rights',
        self::IA_PROCESSING_FEE => 'Processing Fee',
        self::IA_CC_SURCHARGE => 'CC Surcharge',
        self::IA_CASH_DISCOUNT => 'Cash Discount',
    ];
}
