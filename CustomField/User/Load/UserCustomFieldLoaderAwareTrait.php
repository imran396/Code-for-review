<?php
/**
 * Help methods for auction custom field data loading
 *
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 9, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Load;

/**
 * Trait UserCustomFieldLoaderAwareTrait
 * @package Sam\CustomField\User\Load
 */
trait UserCustomFieldLoaderAwareTrait
{
    /**
     * @var UserCustomFieldLoader|null
     */
    protected ?UserCustomFieldLoader $userCustomFieldLoader = null;

    /**
     * @return UserCustomFieldLoader
     */
    protected function getUserCustomFieldLoader(): UserCustomFieldLoader
    {
        if ($this->userCustomFieldLoader === null) {
            $this->userCustomFieldLoader = UserCustomFieldLoader::new();
        }
        return $this->userCustomFieldLoader;
    }

    /**
     * @param UserCustomFieldLoader $userCustomFieldLoader
     * @return static
     * @internal
     */
    public function setUserCustomFieldLoader(UserCustomFieldLoader $userCustomFieldLoader): static
    {
        $this->userCustomFieldLoader = $userCustomFieldLoader;
        return $this;
    }
}
