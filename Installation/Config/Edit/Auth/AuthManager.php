<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           31/05/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Auth;

use Sam\Core\Service\CustomizableClass;

/**
 * Class AuthManager
 * Check user authenticated session flag in $this->isAuthenticated()
 * Setup user authenticated session flag in $this->enableAuthenticated()
 * @package Sam\Installation\Config
 */
class AuthManager extends CustomizableClass
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
     * Check user authenticated session flag
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        $is = isset($_SESSION['installConfigAuth'])
            && $_SESSION['installConfigAuth'];
        return $is;
    }

    /**
     * Setup user authenticated session flag
     * @param bool $enable
     * @return static
     */
    public function enableAuthenticated(bool $enable): static
    {
        $_SESSION['installConfigAuth'] = $enable;
        return $this;
    }
}
