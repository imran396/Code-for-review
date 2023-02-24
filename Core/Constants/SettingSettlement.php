<?php
/**
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/19/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class SettingSettlement
 * @package Sam\Core\Constants
 */
class SettingSettlement
{
    public const CHECK_ADDRESS_TEMPLATE_DEF = '{user_billing_first_name} {user_billing_last_name}
{user_billing_address}
{user_billing_address_2}
{user_billing_address_3}
{user_billing_city}, {user_billing_state} {user_billing_postal_code}';
    public const CHECK_PAYEE_TEMPLATE_DEF = '{user_billing_first_name} {user_billing_last_name}';
    public const CHECK_MEMO_TEMPLATE_DEF = '{settlement_no}';
}
