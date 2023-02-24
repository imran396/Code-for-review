<?php
/**
 * SAM-10664: Refactoring of settings system parameters storage - Move constants
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class SettingUser
 * @package Sam\Core\Constants
 */
class SettingUser
{
    public const P_PAYMENT_GATEWAY = 'P_PAYMENT_GATEWAY';

    // Payment Gateway (setting_user.authorization_use)
    public const PAY_AUTHORIZE_NET = 'A';
    public const PAY_EWAY = 'E';
    public const PAY_NMI = 'M';
    public const PAY_NO_AUTHORIZATION = 'N';
    public const PAY_PAY_TRACE = 'P';
    public const PAY_OPAYO = 'U';

    /**
     * @var string[]
     */
    public const PAYMENT_GATEWAY_NAMES = [
        self::PAY_AUTHORIZE_NET => 'Authorize.net',
        self::PAY_EWAY => 'Eway',
        self::PAY_NMI => 'Nmi',
        self::PAY_NO_AUTHORIZATION => 'No authorization',
        self::PAY_PAY_TRACE => 'PayTrace',
        self::PAY_OPAYO => 'Opayo',
    ];

    // Show User Resume (setting_user.show_user_resume)
    public const SUR_ALL = 'A';
    public const SUR_NONE = 'N';
}
