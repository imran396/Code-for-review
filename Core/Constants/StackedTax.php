<?php
/**
 * SAM-10417: Stacked Tax
 * SAM-10696: Stacked Tax. Tax Definitions management
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

class StackedTax
{
    /**
     * Tax Types
     */
    public const TT_STANDARD = 1;
    public const TT_SPECIAL = 2;
    public const TT_EXEMPTION = 3;
    public const TAX_TYPES = [self::TT_STANDARD, self::TT_SPECIAL, self::TT_EXEMPTION];
    public const TAX_TYPE_NAMES = [
        self::TT_STANDARD => 'Standard',
        self::TT_SPECIAL => 'Special',
        self::TT_EXEMPTION => 'Exemption',
    ];
    public const TAX_TYPE_NAME_TRANSLATIONS = [
        self::TT_STANDARD => 'tax_type.standard',
        self::TT_SPECIAL => 'tax_type.special',
        self::TT_EXEMPTION => 'tax_type.exemption',
    ];

    /**
     * Geo Types
     */
    public const GT_COUNTRY = 1;
    public const GT_STATE = 2;
    public const GT_COUNTY = 3;
    public const GT_CITY = 4;
    public const GEO_TYPES = [self::GT_COUNTRY, self::GT_STATE, self::GT_COUNTY, self::GT_CITY];
    public const GEO_TYPE_NAMES = [
        self::GT_COUNTRY => 'Country',
        self::GT_STATE => 'State',
        self::GT_COUNTY => 'County',
        self::GT_CITY => 'City',
    ];

    public const TAX_AUTHORITY_GEO_TYPES = [
        self::GT_COUNTRY,
        self::GT_STATE,
        self::GT_COUNTY,
        self::GT_CITY,
    ];

    public const RCM_SLIDING = 'sliding';
    public const RCM_TIERED = 'tiered';
    public const RANGE_CALCULATION_METHODS = [self::RCM_SLIDING, self::RCM_TIERED];
    public const RANGE_CALCULATION_METHOD_TRANSLATIONS = [
        self::RCM_SLIDING => 'range.calculation_method.sliding',
        self::RCM_TIERED => 'range.calculation_method.tiered',
    ];

    public const RM_SUM = '+';
    public const RM_GREATER = '>';
    public const RANGE_MODES = [
        self::RM_SUM,
        self::RM_GREATER,
    ];
    public const RANGE_MODE_TRANSLATIONS = [
        self::RM_SUM => 'range.mode.sum',
        self::RM_GREATER => 'range.mode.greater'
    ];

    public const AS_HAMMER_PRICE = 1;
    public const AS_BUYERS_PREMIUM = 2;
    public const AS_SERVICES = 3;
    public const AMOUNT_SOURCES = [
        self::AS_HAMMER_PRICE,
        self::AS_BUYERS_PREMIUM,
        self::AS_SERVICES,
    ];

}
