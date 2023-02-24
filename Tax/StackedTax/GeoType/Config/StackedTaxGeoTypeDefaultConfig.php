<?php
/**
 * Hard-coded configuration of the Stacked Tax Geo Type properties per country.
 * This configuration describes the properties:
 * - whether the Geo location is available for the country (can manage and manipulate);
 * - whether the Geo location is required for the country (must be filled in);
 * - whether the Geo location must be displayed in invoice (even with zero value);
 * - translation keys for the public and admin sites;
 *
 * SAM-10822: Stacked Tax. Location authority chain configuration (Stage 2)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\GeoType\Config;

use Sam\Core\Constants;

class StackedTaxGeoTypeDefaultConfig
{
    public const ADMIN_TRANSLATION_DOMAIN = 'admin_stacked_tax';
    public const PUBLIC_TRANSLATION_DOMAIN = 'stacked_tax_invoices';
    public const DEFAULT = 'default';

    /**
     * Define available Geo Types for every country.
     * Absent elements must not be processed and rendered (are not available).
     * 'required' true means field must be filled on input.
     * 'in_invoice' true means columns must be rendered on invoice.
     */
    public const CONFIG = [
        self::DEFAULT => [
            Constants\StackedTax::GT_COUNTRY => ['required' => true, 'in_invoice' => true],
            Constants\StackedTax::GT_STATE => ['required' => false, 'in_invoice' => false],
            Constants\StackedTax::GT_COUNTY => ['required' => false, 'in_invoice' => false],
            Constants\StackedTax::GT_CITY => ['required' => true, 'in_invoice' => true],
        ],
        Constants\Country::C_USA => [
            Constants\StackedTax::GT_COUNTRY => ['required' => true, 'in_invoice' => false],
            Constants\StackedTax::GT_STATE => ['required' => true, 'in_invoice' => true],
            Constants\StackedTax::GT_COUNTY => ['required' => true, 'in_invoice' => true],
            Constants\StackedTax::GT_CITY => ['required' => true, 'in_invoice' => true],
        ],
        Constants\Country::C_CANADA => [
            Constants\StackedTax::GT_COUNTRY => ['required' => true, 'in_invoice' => true],
            Constants\StackedTax::GT_STATE => ['required' => true, 'in_invoice' => true],
        ],
    ];

    /**
     * 'admin_geo_type', 'admin_invoice_tax', 'admin_invoice_tax_hp', 'admin_invoice_tax_bp' translations are located in "admin_stacked_tax" domain.
     * 'public_invoice_tax', 'public_invoice_tax_hp', 'public_invoice_tax_bp' - public translations in "stacked_tax_invoices.csv".
     * @return array
     */
    public const TRANSLATIONS = [
        self::DEFAULT => [
            Constants\StackedTax::GT_COUNTRY => [
                'admin_geo_type' => 'geo_type.country',
                'admin_invoice_tax' => 'invoice_geo_tax.country',
                'admin_invoice_tax_hp' => 'invoice_geo_tax_hp.country',
                'admin_invoice_tax_bp' => 'invoice_geo_tax_bp.country',
                'public_invoice_tax' => 'GOODS_GEO_TAX_COUNTRY',
                'public_invoice_tax_hp' => 'GOODS_GEO_TAX_HP_COUNTRY',
                'public_invoice_tax_bp' => 'GOODS_GEO_TAX_BP_COUNTRY',
            ],
            Constants\StackedTax::GT_STATE => [
                'admin_geo_type' => 'geo_type.state',
                'admin_invoice_tax' => 'invoice_geo_tax.state',
                'admin_invoice_tax_hp' => 'invoice_geo_tax_hp.state',
                'admin_invoice_tax_bp' => 'invoice_geo_tax_bp.state',
                'public_invoice_tax' => 'GOODS_GEO_TAX_STATE',
                'public_invoice_tax_hp' => 'GOODS_GEO_TAX_HP_STATE',
                'public_invoice_tax_bp' => 'GOODS_GEO_TAX_BP_STATE',
            ],
            Constants\StackedTax::GT_COUNTY => [
                'admin_geo_type' => 'geo_type.county',
                'admin_invoice_tax' => 'invoice_geo_tax.county',
                'admin_invoice_tax_hp' => 'invoice_geo_tax_hp.county',
                'admin_invoice_tax_bp' => 'invoice_geo_tax_bp.county',
                'public_invoice_tax' => 'GOODS_GEO_TAX_COUNTY',
                'public_invoice_tax_hp' => 'GOODS_GEO_TAX_HP_COUNTY',
                'public_invoice_tax_bp' => 'GOODS_GEO_TAX_BP_COUNTY',
            ],
            Constants\StackedTax::GT_CITY => [
                'admin_geo_type' => 'geo_type.city',
                'admin_invoice_tax' => 'invoice_geo_tax.city',
                'admin_invoice_tax_hp' => 'invoice_geo_tax_hp.city',
                'admin_invoice_tax_bp' => 'invoice_geo_tax_bp.city',
                'public_invoice_tax' => 'GOODS_GEO_TAX_CITY',
                'public_invoice_tax_hp' => 'GOODS_GEO_TAX_HP_CITY',
                'public_invoice_tax_bp' => 'GOODS_GEO_TAX_BP_CITY',
            ],
        ],
        Constants\Country::C_CANADA => [
            Constants\StackedTax::GT_COUNTRY => [
                'admin_geo_type' => 'geo_type.country',
                'admin_invoice_tax' => 'invoice_geo_tax.ca.country',
                'admin_invoice_tax_hp' => 'invoice_geo_tax_hp.ca.country',
                'admin_invoice_tax_bp' => 'invoice_geo_tax_bp.ca.country',
                'public_invoice_tax' => 'GOODS_GEO_TAX_CA_COUNTRY',
                'public_invoice_tax_hp' => 'GOODS_GEO_TAX_HP_CA_COUNTRY',
                'public_invoice_tax_bp' => 'GOODS_GEO_TAX_BP_CA_COUNTRY',
            ],
            Constants\StackedTax::GT_STATE => [
                'admin_geo_type' => 'geo_type.ca.state',
                'admin_invoice_tax' => 'invoice_geo_tax.ca.state',
                'admin_invoice_tax_hp' => 'invoice_geo_tax_hp.ca.state',
                'admin_invoice_tax_bp' => 'invoice_geo_tax_bp.ca.state',
                'public_invoice_tax' => 'GOODS_GEO_TAX_CA_STATE',
                'public_invoice_tax_hp' => 'GOODS_GEO_TAX_HP_CA_STATE',
                'public_invoice_tax_bp' => 'GOODS_GEO_TAX_BP_CA_STATE',
            ],
        ],
    ];
}
