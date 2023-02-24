<?php
/**
 * User Custom Field List Data Loader Create Trait
 *
 * SAM-6285: Refactor User Custom Field List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 13, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\UserCustomFieldListForm\Load;

/**
 * Trait UserCustomFieldListDataLoaderCreateTrait
 */
trait UserCustomFieldListDataLoaderCreateTrait
{
    protected ?UserCustomFieldListDataLoader $userCustomFieldListDataLoader = null;

    /**
     * @return UserCustomFieldListDataLoader
     */
    protected function createUserCustomFieldListDataLoader(): UserCustomFieldListDataLoader
    {
        $userCustomFieldListDataLoader = $this->userCustomFieldListDataLoader ?: UserCustomFieldListDataLoader::new();
        return $userCustomFieldListDataLoader;
    }

    /**
     * @param UserCustomFieldListDataLoader $userCustomFieldListDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setUserCustomFieldListDataLoader(UserCustomFieldListDataLoader $userCustomFieldListDataLoader): static
    {
        $this->userCustomFieldListDataLoader = $userCustomFieldListDataLoader;
        return $this;
    }
}
