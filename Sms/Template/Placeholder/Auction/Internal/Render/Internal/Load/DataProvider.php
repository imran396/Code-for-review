<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\Auction\Internal\Render\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;

/**
 * Class DataProvider
 * @package Sam\Sms\Template\Placeholder\Auction\Internal\Render\Internal\Load
 * @internal
 */
class DataProvider extends CustomizableClass
{
    use TimezoneLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadTimezoneLocation(int $timezoneId, bool $isReadOnlyDb = false): ?string
    {
        return $this->getTimezoneLoader()->load($timezoneId, $isReadOnlyDb)->Location ?? null;
    }
}
