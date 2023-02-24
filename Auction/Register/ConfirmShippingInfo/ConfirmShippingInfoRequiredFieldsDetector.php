<?php
/**
 * SAM-6261: Replace Symfony validator and apply ResultStatusCollector for Confirm Shipping Info page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 03, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Register\ConfirmShippingInfo;

use Sam\Core\Service\CustomizableClass;
use Sam\Settings\User\UserSettingCheckerCreateTrait;

/**
 * This class is responsible for detecting required fields which depends on system settings
 * and shipping country
 *
 * Class ConfirmShippingInfoRequiredFieldsDetector
 * @package Sam\Auction\Register\ConfirmShippingInfo
 */
class ConfirmShippingInfoRequiredFieldsDetector extends CustomizableClass
{
    use UserSettingCheckerCreateTrait;

    private const REQUIRED_FIELDS = [
        'address',
        'city',
        'contactType',
        'country',
        'firstName',
        'lastName',
        'phone',
        'state',
        'zip',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return required fields which may vary depending on the input
     * @return array
     */
    public function detect(): array
    {
        return $this->createUserSettingChecker()->isProfileShippingRequired()
            ? self::REQUIRED_FIELDS
            : [];
    }
}
