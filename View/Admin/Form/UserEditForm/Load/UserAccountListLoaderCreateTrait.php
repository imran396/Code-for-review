<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\UserEditForm\Load;

/**
 * Trait UserAccountListLoaderCreateTrait
 * @package Sam\View\Admin\Form\UserEditForm\Load
 */
trait UserAccountListLoaderCreateTrait
{
    protected ?UserAccountListLoader $userAccountListLoader = null;

    /**
     * @return UserAccountListLoader
     */
    protected function createUserAccountListLoader(): UserAccountListLoader
    {
        return $this->userAccountListLoader ?: UserAccountListLoader::new();
    }

    /**
     * @param UserAccountListLoader $userAccountListLoader
     * @return static
     * @internal
     */
    public function setUserAccountListLoader(UserAccountListLoader $userAccountListLoader): static
    {
        $this->userAccountListLoader = $userAccountListLoader;
        return $this;
    }
}
