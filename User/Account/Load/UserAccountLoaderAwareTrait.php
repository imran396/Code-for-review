<?php
/**
 *
 * SAM-4738: UserAccount management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-27
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Account\Load;

/**
 * Trait UserAccountLoaderAwareTrait
 * @package Sam\User\Account\Load
 */
trait UserAccountLoaderAwareTrait
{
    protected ?UserAccountLoader $userAccountLoader = null;

    /**
     * @return UserAccountLoader
     */
    protected function getUserAccountLoader(): UserAccountLoader
    {
        if ($this->userAccountLoader === null) {
            $this->userAccountLoader = UserAccountLoader::new();
        }
        return $this->userAccountLoader;
    }

    /**
     * @param UserAccountLoader $userAccountLoader
     * @return static
     * @internal
     */
    public function setUserAccountLoader(UserAccountLoader $userAccountLoader): static
    {
        $this->userAccountLoader = $userAccountLoader;
        return $this;
    }
}
