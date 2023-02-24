<?php
/**
 * SAM-10117: Data truncation vulnerability for user.username input
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 21, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\User\Username\Sanitize;

use Sam\Core\Service\CustomizableClass;
use User;

/**
 * Class UsernamePureSanitizer
 * @package Sam\Core\Entity\Model\User\Username\Sanitize
 */
class UsernamePureSanitizer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function normalize(string $username): string
    {
        $username = trim($username);
        $username = mb_substr($username, 0, User::USERNAME_MAX_LENGTH);
        $username = rtrim($username);
        return $username;
    }
}
