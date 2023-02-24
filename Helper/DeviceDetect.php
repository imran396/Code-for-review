<?php

/**
 * A helper to detect is a mobile device a user use or not.
 * The method based on suggestion of the Mozilla:
 * "In summary, we recommend looking for the string “Mobi” anywhere in the User Agent to detect a mobile device
 * If the device is large enough that it's not marked with “Mobi”, you should serve your desktop site
 * (which, as a best practice, should support touch input anyway,
 * as more desktop machines are appearing with touchscreens)."
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Browser_detection_using_the_user_agent
 *
 * @copyright   2018 Bidpath, Inc.
 * @author      Maxim Lyubetskiy
 * @package     com.swb.sam2
 * @version     SVN: $Id$
 * @since       Oct 20, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Helper;

use Sam\Core\Service\CustomizableClass;


/**
 * Class DeviceDetect
 */
class DeviceDetect extends CustomizableClass
{

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $userAgent
     * @return bool
     */
    public function isMobile(string $userAgent): bool
    {
        $isMobile = false !== stripos($userAgent, "Mobi");
        return $isMobile;
    }

}
