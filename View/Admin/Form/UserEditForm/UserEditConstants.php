<?php
/**
 * User Edit Constants
 *
 * SAM-6286: Refactor User Edit page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 14, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\UserEditForm;

/**
 * Class UserEditConstants
 */
class UserEditConstants
{
    // constants for user logs
    public const ORD_NOTE = 'note';
    public const ORD_TIME_LOG = 'time_log';
    public const ORD_DEFAULT = self::ORD_TIME_LOG;

    // constants for ip address
    public const ORD_IP_ADDRESS = 'ip_address';
}