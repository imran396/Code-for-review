<?php
/**
 * SAM-4022: TimezoneLoader and TimezoneExistenceChecker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 15, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Timezone\Validate;

use Sam\Core\Service\CustomizableClass;

/**
 * Class TimezoneExistenceChecker
 */
class TimezoneExistenceChecker extends CustomizableClass
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
     * @param string|null $code null leads to false
     * @return bool
     */
    public function existByCode(?string $code): bool
    {
        return timezone_name_from_abbr($code) !== false;
    }
}
