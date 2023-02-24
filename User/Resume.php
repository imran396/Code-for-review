<?php
/**
 * Helper for user resume
 *
 * @copyright       2016 SAM
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           2 Jan, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class Resume
 * @package Sam\User
 */
class Resume extends CustomizableClass
{
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if resume is available
     * @return bool
     */
    public function isAvailable(): bool
    {
        $sm = $this->getSettingsManager();
        $enableUserResume = $sm->getForMain(Constants\Setting::ENABLE_USER_RESUME);
        $showUserResume = $sm->getForMain(Constants\Setting::SHOW_USER_RESUME);
        $isAvailable = $enableUserResume
            && $showUserResume === Constants\SettingUser::SUR_ALL;
        return $isAvailable;
    }

}
