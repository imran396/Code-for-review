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
 * Class SettingSystem
 * @package Sam\Core\Constants
 */
class SettingSystem
{
    // Email Format (setting_system.email_format)
    public const EF_PLAIN = 'P';
    public const EF_HTML = 'H';

    public const WAVEBID_ENDPOINT_MAX_LENGTH = 512;
    public const WAVEBID_UAT_MAX_LENGTH = 255;
}
