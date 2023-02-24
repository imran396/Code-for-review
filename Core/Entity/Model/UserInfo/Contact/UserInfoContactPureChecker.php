<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\UserInfo\Contact;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class UserInfoContactChecker
 * @package Sam\Core\Entity\Model\UserInfo\Contact
 */
class UserInfoContactPureChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- IdentificationType ---

    /**
     * Check if user's identification type is "None".
     * @param int|null $identificationType
     * @return bool
     */
    public function isNoneIdentificationType(?int $identificationType): bool
    {
        return $identificationType === Constants\User::IDT_NONE;
    }

    // --- PhoneType ---

    /**
     * Check if user's phone type is "Work".
     * @param int|null $phoneType
     * @return bool
     */
    public function isWorkPhoneType(?int $phoneType): bool
    {
        return $phoneType === Constants\User::PT_WORK;
    }

    /**
     * Check if user's phone type is "Home".
     * @param int|null $phoneType
     * @return bool
     */
    public function isHomePhoneType(?int $phoneType): bool
    {
        return $phoneType === Constants\User::PT_HOME;
    }

    /**
     * Check if user's phone type is "Mobile".
     * @param int|null $phoneType
     * @return bool
     */
    public function isMobilePhoneType(?int $phoneType): bool
    {
        return $phoneType === Constants\User::PT_MOBILE;
    }

    /**
     * Check if user's phone type is "None".
     * @param int|null $phoneType
     * @return bool
     */
    public function isNonePhoneType(?int $phoneType): bool
    {
        return $phoneType === Constants\User::PT_NONE;
    }
}
