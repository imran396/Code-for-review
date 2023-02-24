<?php
/**
 * User password encryption / decryption
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           15 Oct, 2015
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Password;

use Sam\Core\Service\CustomizableClass;

/**
 * Class HashHelper
 * @package Sam\User\Password
 */
class HashHelper extends CustomizableClass
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
     * Encrypt password
     * @param string $password
     * @return string
     */
    public function encrypt(string $password): string
    {
        $encrypted = password_hash($password, PASSWORD_BCRYPT);
        return $encrypted;
    }

    /**
     * @param string $password
     * @return string
     */
    public function normalizeAndEncrypt(string $password): string
    {
        $password = trim($password);
        return $this->encrypt($password);
    }

    /**
     * Verify password
     * @param string $password Checking plain password
     * @param string $actualHash Hash of real password
     * @return bool
     */
    public function verify(string $password, string $actualHash): bool
    {
        $verified = password_verify($password, $actualHash);
        return $verified;
    }
}
