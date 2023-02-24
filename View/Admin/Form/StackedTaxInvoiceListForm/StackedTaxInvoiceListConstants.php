<?php
/**
 * Invoice List Constants
 *
 * SAM-6092: Refactor Invoice List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 16, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\StackedTaxInvoiceListForm;

class StackedTaxInvoiceListConstants
{
    public const ORD_INVOICE_NO = 'InvoiceNo';
    public const ORD_ISSUED_DATE = 'IssuedDate';
    public const ORD_USER = 'User';
    public const ORD_NAME = 'Name';
    public const ORD_STATE = 'State';
    public const ORD_ZIP = 'ZIP';
    public const ORD_STATUS = 'Status';
    public const ORD_SENT_DATE = 'SentDate';
    public const ORD_SALE = 'Sale';
    public const ORD_HP = 'Hp';
    public const ORD_HP_TAX = 'HpTax';
    public const ORD_HP_WITH_TAX = 'HpWithTax';
    public const ORD_BP = 'Bp';
    public const ORD_BP_TAX = 'BpTax';
    public const ORD_BP_WITH_TAX = 'BpWithTax';
    public const ORD_COUNTRY_TAX = 'CountryTax';
    public const ORD_STATE_TAX = 'StateTax';
    public const ORD_COUNTY_TAX = 'CountyTax';
    public const ORD_CITY_TAX = 'CityTax';
    public const ORD_SERVICES = 'Services';
    public const ORD_SERVICES_TAX = 'ServicesTax';
    public const ORD_SERVICES_WITH_TAX = 'ServicesWithTax';
    public const ORD_PAYMENT_TOTAL = 'PaymentTotal';
    public const ORD_BALANCE_DUE = 'BalanceDue';
    public const ORD_INVOICE_TOTAL = 'InvoiceTotal';
    public const ORD_CURRENCY = 'Currency';
    public const ORD_DEFAULT = 'Id';
    public const CSS_COLUMN_HP = 'stacked-taxes-hp';
    public const CSS_COLUMN_HP_TAX = 'stacked-taxes-hp-tax';
    public const CSS_COLUMN_HP_PLUS_TAX = 'stacked-taxes-hp-plus-tax';
    public const CSS_COLUMN_BP = 'stacked-taxes-bp';
    public const CSS_COLUMN_BP_TAX = 'stacked-taxes-bp-tax';
    public const CSS_COLUMN_BP_PLUS_TAX = 'stacked-taxes-bp-plus-tax';
    public const CSS_COLUMN_COUNTRY_TAX = 'stacked-taxes-country-tax';
    public const CSS_COLUMN_STATE_TAX = 'stacked-taxes-state-tax';
    public const CSS_COLUMN_COUNTY_TAX = 'stacked-taxes-county-tax';
    public const CSS_COLUMN_CITY_TAX = 'stacked-taxes-city-tax';
    public const CSS_COLUMN_SERVICES = 'stacked-taxes-services';
    public const CSS_COLUMN_SERVICES_TAX = 'stacked-taxes-services-tax';
    public const CSS_COLUMN_SERVICES_PLUS_TAX = 'stacked-taxes-services-plus-tax';
    public const CSS_COLUMN_INVOICE_TOTAL = 'stacked-taxes-invoice-total';
    public const CSS_COLUMN_PAYMENT_TOTAL = 'stacked-taxes-payment-total';
    public const CSS_COLUMN_BALANCE_DUE = 'stacked-taxes-balance-due';

    /** @var string[] */
    public static array $invoiceListOrderColumns = [
        self::ORD_INVOICE_NO => "Invoice#",
        self::ORD_ISSUED_DATE => "Issued Date",
        self::ORD_SENT_DATE => "Sent Date",
        self::ORD_USER => "User",
        self::ORD_NAME => "Name",
        self::ORD_STATE => "Buyer State",
        self::ORD_ZIP => "Buyer ZIP",
        self::ORD_HP => "HP",
        self::ORD_HP_TAX => "HP Tax",
        self::ORD_HP_WITH_TAX => "HP with Tax",
        self::ORD_BP => "BP",
        self::ORD_BP_TAX => "BP Tax",
        self::ORD_BP_WITH_TAX => "BP with Tax",
        self::ORD_COUNTRY_TAX => "Country Tax",
        self::ORD_STATE_TAX => "State Tax",
        self::ORD_COUNTY_TAX => "County Tax",
        self::ORD_CITY_TAX => "City Tax",
        self::ORD_SERVICES => "Services",
        self::ORD_SERVICES_TAX => "Services Tax",
        self::ORD_SERVICES_WITH_TAX => "Services with Tax",
        self::ORD_INVOICE_TOTAL => "Invoice Total",
        self::ORD_PAYMENT_TOTAL => "Payment Total",
        self::ORD_BALANCE_DUE => "Balance Due",
        self::ORD_STATUS => "Status",
        self::ORD_CURRENCY => "Currency",
    ];
}
