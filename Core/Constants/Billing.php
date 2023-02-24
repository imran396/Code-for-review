<?php
/**
 * SAM-7644: Replace ENUM to TINYINT(2) datatype
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class Billing
 * @package Sam\Core\Constants
 */
class Billing
{
    // Credit Card Verification ways
    public const CCV_NONE = 0;
    public const CCV_AUTH = 1;
    public const CCV_CAPTURE = 2;
    /** @var int[] */
    public const CC_VERIFICATIONS = [
        self::CCV_NONE,
        self::CCV_AUTH,
        self::CCV_CAPTURE,
    ];

    public const CC_VERIFICATION_NAMES = [
        self::CCV_NONE => 'NONE',
        self::CCV_AUTH => 'AUTHORIZE',
        self::CCV_CAPTURE => 'CAPTURE',
    ];

    public const PAY_ACC_MODE_TEST = 'T';
    public const PAY_ACC_MODE_PRODUCTION = 'P';
    /** @var string[] */
    public const PAYMENT_ACCOUNT_MODE_NAMES = [
        self::PAY_ACC_MODE_TEST => 'Test',
        self::PAY_ACC_MODE_PRODUCTION => 'Production',
    ];

    public const PAY_ACC_TYPE_DEVELOPER = 'D';
    public const PAY_ACC_TYPE_MERCHANT = 'M';
    /** @var string[] */
    public const PAYMENT_ACCOUNT_TYPE_NAMES = [
        self::PAY_ACC_TYPE_DEVELOPER => 'Developer',
        self::PAY_ACC_TYPE_MERCHANT => 'Merchant',
    ];
}
