<?php
/**
 * User Data Loader Create Trait
 *
 * SAM-6286: Refactor User Edit page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 14, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\UserEditForm\Load;

/**
 * Trait UserDataLoaderCreateTrait
 */
trait UserDataLoaderCreateTrait
{
    protected ?UserDataLoader $userDataLoader = null;

    /**
     * @return UserDataLoader
     */
    protected function createUserDataLoader(): UserDataLoader
    {
        $userDataLoader = $this->userDataLoader ?: UserDataLoader::new();
        return $userDataLoader;
    }

    /**
     * @param UserDataLoader $userDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setUserDataLoader(UserDataLoader $userDataLoader): static
    {
        $this->userDataLoader = $userDataLoader;
        return $this;
    }
}
