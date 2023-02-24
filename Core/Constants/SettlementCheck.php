<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           11-23, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class SettlementCheck
 * @package Sam\Core\Constants
 */
class SettlementCheck
{
    public const S_NONE = 0;
    public const S_CREATED = 1;
    public const S_PRINTED = 2;
    public const S_POSTED = 3;
    public const S_CLEARED = 4;
    public const S_VOIDED = 5;

    /** @var string[] */
    public const STATUS_NAMES = [
        self::S_NONE => 'None',
        self::S_CREATED => 'Created',
        self::S_PRINTED => 'Printed',
        self::S_POSTED => 'Posted',
        self::S_CLEARED => 'Cleared',
        self::S_VOIDED => 'Voided',
    ];

    public const ADDRESS_TEMPLATE_DEFAULT = <<<TEXT
{user_billing_first_name} {user_billing_last_name}
{user_billing_address}
{user_billing_address_2}
{user_billing_address_3}
{user_billing_city}, {user_billing_state} {user_billing_postal_code}
TEXT;
    public const PAYEE_TEMPLATE_DEFAULT = '{user_billing_first_name} {user_billing_last_name}';
    public const MEMO_TEMPLATE_DEFAULT = '{settlement_no}';
}
