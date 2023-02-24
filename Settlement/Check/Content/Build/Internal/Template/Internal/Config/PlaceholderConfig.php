<?php
/**
 * Placeholder configuration, like mapping to DB fields.
 *
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Content\Build\Internal\Template\Internal\Config;

use Sam\Settlement\Check\Content\Common\Constants\PlaceholderConstants;

class PlaceholderConfig
{
    /** @var string[] */
    public const USER_RESULT_FIELDS = [
        'ui_first_name' => 'ui.first_name',
        'ui_last_name' => 'ui.last_name',
        'ui_company_name' => 'ui.company_name',
        'ub_first_name' => 'ub.first_name',
        'ub_last_name' => 'ub.last_name',
        'ub_company_name' => 'ub.company_name',
        'ub_address' => 'ub.address',
        'ub_address2' => 'ub.address2',
        'ub_address3' => 'ub.address3',
        'ub_city' => 'ub.city',
        'ub_zip' => 'ub.zip',
        'ub_country' => 'ub.country',
        'ub_state' => 'ub.state',
        'us_first_name' => 'us.first_name',
        'us_last_name' => 'us.last_name',
        'us_company_name' => 'us.company_name',
        'us_address' => 'us.address',
        'us_address2' => 'us.address2',
        'us_address3' => 'us.address3',
        'us_city' => 'us.city',
        'us_zip' => 'us.zip',
        'us_country' => 'us.country',
        'us_state' => 'us.state',
    ];

    /** @var string[][][] */
    public const USER_PLACEHOLDERS_CONFIG = [
        PlaceholderConstants::PL_USER_INFO_FIRST_NAME => [
            'select' => ['ui_first_name'],
        ],
        PlaceholderConstants::PL_USER_INFO_LAST_NAME => [
            'select' => ['ui_last_name'],
        ],
        PlaceholderConstants::PL_USER_INFO_COMPANY_NAME => [
            'select' => ['ui_company_name'],
        ],
        PlaceholderConstants::PL_USER_BILLING_FIRST_NAME => [
            'select' => ['ub_first_name'],
        ],
        PlaceholderConstants::PL_USER_BILLING_LAST_NAME => [
            'select' => ['ub_last_name'],
        ],
        PlaceholderConstants::PL_USER_BILLING_COMPANY_NAME => [
            'select' => ['ub_company_name'],
        ],
        PlaceholderConstants::PL_USER_BILLING_ADDRESS => [
            'select' => ['ub_address'],
        ],
        PlaceholderConstants::PL_USER_BILLING_ADDRESS_2 => [
            'select' => ['ub_address2'],
        ],
        PlaceholderConstants::PL_USER_BILLING_ADDRESS_3 => [
            'select' => ['ub_address3'],
        ],
        PlaceholderConstants::PL_USER_BILLING_CITY => [
            'select' => ['ub_city'],
        ],
        PlaceholderConstants::PL_USER_BILLING_POSTAL_CODE => [
            'select' => ['ub_zip'],
        ],
        PlaceholderConstants::PL_USER_BILLING_COUNTRY_ABBR => [
            'select' => ['ub_country'],
        ],
        PlaceholderConstants::PL_USER_BILLING_COUNTRY => [
            'select' => ['ub_country'],
        ],
        PlaceholderConstants::PL_USER_BILLING_STATE => [
            'select' => ['ub_state', 'ub_country'],
        ],
        PlaceholderConstants::PL_USER_BILLING_STATE_ABBR => [
            'select' => ['ub_state'],
        ],
        PlaceholderConstants::PL_USER_SHIPPING_FIRST_NAME => [
            'select' => ['us_first_name'],
        ],
        PlaceholderConstants::PL_USER_SHIPPING_LAST_NAME => [
            'select' => ['us_last_name'],
        ],
        PlaceholderConstants::PL_USER_SHIPPING_COMPANY_NAME => [
            'select' => ['us_company_name'],
        ],
        PlaceholderConstants::PL_USER_SHIPPING_ADDRESS => [
            'select' => ['us_address'],
        ],
        PlaceholderConstants::PL_USER_SHIPPING_ADDRESS_2 => [
            'select' => ['us_address2'],
        ],
        PlaceholderConstants::PL_USER_SHIPPING_ADDRESS_3 => [
            'select' => ['us_address3'],
        ],
        PlaceholderConstants::PL_USER_SHIPPING_CITY => [
            'select' => ['us_city'],
        ],
        PlaceholderConstants::PL_USER_SHIPPING_POSTAL_CODE => [
            'select' => ['us_zip'],
        ],
        PlaceholderConstants::PL_USER_SHIPPING_COUNTRY_ABBR => [
            'select' => ['us_country'],
        ],
        PlaceholderConstants::PL_USER_SHIPPING_COUNTRY => [
            'select' => ['us_country'],
        ],
        PlaceholderConstants::PL_USER_SHIPPING_STATE => [
            'select' => ['us_state', 'us_country'],
        ],
        PlaceholderConstants::PL_USER_SHIPPING_STATE_ABBR => [
            'select' => ['us_state'],
        ],
    ];
}
