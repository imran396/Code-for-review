<?php
/**
 * SAM-8893: Relocate pure functions from GeneralValidator to \Sam\Core namespace
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\User\Username\Validate;

use Sam\Core\Email\Validate\EmailAddressChecker;
use Sam\Core\Service\CustomizableClass;
use User;

/**
 * Class UsernameChecker
 * @package Sam\Core\User\Validate
 */
class UsernamePureChecker extends CustomizableClass
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
     * @param string $username
     * @return bool
     * #[Pure]
     */
    public function isValidFormat(string $username): bool
    {
        if (!$this->isValidLength($username)) {
            return false;
        }

        // accepts valid email as usernames
        if (EmailAddressChecker::new()->isEmail($username)) {
            return true;
        }

        return preg_match('/^[ A-Za-z0-9#@_.+-]+$/i', $username);
    }

    /**
     * Checks, if username length meets respective DB column restriction defined by VARCHAR(X) limit.
     * @param string $username
     * @return bool
     */
    public function isValidLength(string $username): bool
    {
        return mb_strlen($username) <= User::USERNAME_MAX_LENGTH;
    }
}
