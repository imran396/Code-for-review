<?php
/**
 * SAM-10136: Implement conditional logic in print check template field Payee
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\User\SettlementCheck\Payee\Internal;

use Sam\Core\Constants;

/**
 * Class ConfigManager
 * @package Sam\Details\User\SettlementCheck\Payee\Internal
 */
class ConfigManager extends \Sam\Details\Core\ConfigManager
{
    /**
     * @var array<array<string, array{lang?: string[], select: string[]|string, available?: bool, observable?: bool}>>
     */
    protected array $keysConfig = [
        Constants\Placeholder::REGULAR => [
            Constants\UserDetail::PL_COMPANY_NAME => [
                'select' => ['user_info_company_name'],
            ],
            Constants\UserDetail::PL_FIRST_NAME => [
                'select' => ['user_info_first_name'],
            ],
            Constants\UserDetail::PL_LAST_NAME => [
                'select' => ['user_info_last_name'],
            ],
            Constants\UserDetail::PL_USER_BILLING_FIRST_NAME => [
                'select' => ['user_billing_first_name'],
            ],
            Constants\UserDetail::PL_USER_BILLING_LAST_NAME => [
                'select' => ['user_billing_last_name'],
            ],
            Constants\UserDetail::PL_USER_BILLING_COMPANY_NAME => [
                'select' => ['user_billing_company_name'],
            ],
            Constants\UserDetail::PL_USER_SHIPPING_COMPANY_NAME => [
                'select' => ['user_shipping_company_name'],
            ],
            Constants\UserDetail::PL_USER_SHIPPING_FIRST_NAME => [
                'select' => ['user_shipping_first_name'],
            ],
            Constants\UserDetail::PL_USER_SHIPPING_LAST_NAME => [
                'select' => ['user_shipping_last_name'],
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
