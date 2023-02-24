<?php
/**
 * SAM-10136: Implement conditional logic in print check template field Payee
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\User\SettlementCheck\Address\Internal\Config;

use Sam\Core\Constants;

/**
 * Class ConfigManager
 * @package Sam\Details\User\SettlementCheck\Address\Internal\Config
 */
class ConfigManager extends \Sam\Details\Core\ConfigManager
{
    /**
     * @var array<array<string, array{lang?: string[], select: string[]|string, available?: bool, observable?:bool}>>
     */
    protected array $keysConfig = [
        Constants\Placeholder::REGULAR => [
            Constants\UserDetail::PL_USER_BILLING_ADDRESS => [
                'select' => ['user_billing_address'],
            ],
            Constants\UserDetail::PL_USER_BILLING_ADDRESS_2 => [
                'select' => ['user_billing_address_2'],
            ],
            Constants\UserDetail::PL_USER_BILLING_ADDRESS_3 => [
                'select' => ['user_billing_address_3'],
            ],
            Constants\UserDetail::PL_USER_BILLING_CITY => [
                'select' => ['user_billing_city'],
            ],
            Constants\UserDetail::PL_USER_BILLING_COMPANY_NAME => [
                'select' => ['user_billing_company_name'],
            ],
            Constants\UserDetail::PL_USER_BILLING_COUNTRY => [
                'select' => ['user_billing_country'],
            ],
            Constants\UserDetail::PL_USER_BILLING_COUNTRY_ABBR => [
                'select' => ['user_billing_country'],
            ],
            Constants\UserDetail::PL_USER_BILLING_FIRST_NAME => [
                'select' => ['user_billing_first_name'],
            ],
            Constants\UserDetail::PL_USER_BILLING_LAST_NAME => [
                'select' => ['user_billing_last_name'],
            ],
            Constants\UserDetail::PL_USER_BILLING_POSTAL_CODE => [
                'select' => ['user_billing_postal_code'],
            ],
            Constants\UserDetail::PL_USER_BILLING_STATE => [
                'select' => ['user_billing_state', 'user_billing_country'],
            ],
            Constants\UserDetail::PL_USER_BILLING_STATE_ABBR => [
                'select' => ['user_billing_state'],
            ],
            Constants\UserDetail::PL_USER_SHIPPING_ADDRESS => [
                'select' => ['user_shipping_address'],
            ],
            Constants\UserDetail::PL_USER_SHIPPING_ADDRESS_2 => [
                'select' => ['user_shipping_address_2'],
            ],
            Constants\UserDetail::PL_USER_SHIPPING_ADDRESS_3 => [
                'select' => ['user_shipping_address_3'],
            ],
            Constants\UserDetail::PL_USER_SHIPPING_CITY => [
                'select' => ['user_shipping_city'],
            ],
            Constants\UserDetail::PL_USER_SHIPPING_COMPANY_NAME => [
                'select' => ['user_shipping_company_name'],
            ],
            Constants\UserDetail::PL_USER_SHIPPING_COUNTRY => [
                'select' => ['user_shipping_country'],
            ],
            Constants\UserDetail::PL_USER_SHIPPING_COUNTRY_ABBR => [
                'select' => ['user_shipping_country'],
            ],
            Constants\UserDetail::PL_USER_SHIPPING_FIRST_NAME => [
                'select' => ['user_shipping_first_name'],
            ],
            Constants\UserDetail::PL_USER_SHIPPING_LAST_NAME => [
                'select' => ['user_shipping_last_name'],
            ],
            Constants\UserDetail::PL_USER_SHIPPING_POSTAL_CODE => [
                'select' => ['user_shipping_postal_code'],
            ],
            Constants\UserDetail::PL_USER_SHIPPING_STATE => [
                'select' => ['user_shipping_state', 'user_shipping_country'],
            ],
            Constants\UserDetail::PL_USER_SHIPPING_STATE_ABBR => [
                'select' => ['user_shipping_state'],
            ],
        ]
    ];

    /**
     * Class instantiation method
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
