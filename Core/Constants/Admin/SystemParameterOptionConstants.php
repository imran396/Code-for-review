<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01/29/21
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 =415 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * We use it in JS only!!
 *
 * Class SystemParametersOptionConstants
 */
class SystemParameterOptionConstants
{
    public const OPTION_USER = 'user_option';
    public const OPTION_LIVE_AUCTION = 'live_auction';
    public const OPTION_HYBRID_AUCTION = 'hybrid_auction';
    public const OPTION_TIMED_ONLINE_AUCTION = 'timed_online_auction';
    public const OPTION_INVOICING = 'invoicing';
    public const OPTION_PAYMENTS = 'payments';
    public const OPTION_LAYOUT_AND_SITE_CUSTOMIZATION = 'layout_and_site_customization';
    public const OPTION_SYSTEM = 'system';
    public const OPTION_ADMIN = 'admin_option';
    public const OPTION_AUCTION = 'auction';
    public const OPTION_CUSTOM_TEMPLATE = 'custom_template';
    public const OPTION_INTEGRATIONS = 'integrations';
}
