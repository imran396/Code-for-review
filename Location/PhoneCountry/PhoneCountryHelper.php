<?php
/**
 * SAM-4678: Phone country code helper
 *
 * @author        Vahagn Hovsepyan
 * @since         Feb 08, 2019
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Location\PhoneCountry;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class PhoneCountryHelper
 */
class PhoneCountryHelper extends CustomizableClass
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function getCountryNamesToCodes(): array
    {
        asort(Constants\PhoneCountry::$countryNamesToCodes);
        return array_unique(Constants\PhoneCountry::$countryNamesToCodes);
    }
}
