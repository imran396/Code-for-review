<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/13/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 =415 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class ChangePasswordFormConstants
 */
class ChangePasswordFormConstants
{
    public const CID_TXT_CURRENT_PASSWORD = 'cpwd';
    public const CID_TXT_NEW_PASSWORD = 'npwd';
    public const CID_TXT_CONFIRM_PASSWORD = 'cnpwd';
    public const CID_BTN_CHANGE_PASSWORD = 'aq7';
    public const CID_BTN_CLOSE = 'aq8';
    public const CID_BLK_FLASH_NOTIFICATION = 'flash-notification';

    public const CLASS_BLK_PASSWORD_INDICATOR = 'password-indicator';
    public const CLASS_BLK_VALIDATION_ERROR = 'validation-error';
}
