<?php
/**
 * User password encryption / description
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
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;

/**
 * SAM-1238: Increased password security:
 * This class and its methods-wrappers where used only for password encrypting
 * and because from now on we use one-way cryptography (\Sam\User\Password\Hash),
 * this class is deprecated and should not be used.
 * @deprecated
 * @see \Sam\User\Password\HashHelper
 */
class Crypto extends CustomizableClass
{
    use BlockCipherProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Decrypt password
     * @param string $decryptedPassword
     * @return string
     */
    public function decrypt(string $decryptedPassword): string
    {
        try {
            $plainPassword = $this->createBlockCipherProvider()->construct()->decrypt($decryptedPassword);
        } catch (\QCryptographyException) {
            // SAM-1238: Increased password security:
            // As we use one way cryptography for saving passwords, it's not possible to decrypt passwords anymore
            $plainPassword = '';
        }
        return $plainPassword;
    }
}
