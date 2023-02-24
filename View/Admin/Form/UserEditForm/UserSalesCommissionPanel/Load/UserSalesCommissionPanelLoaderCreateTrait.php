<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\UserEditForm\UserSalesCommissionPanel\Load;

/**
 * Trait UserSalesCommissionPanelLoaderCreateTrait
 * @package Sam\View\Admin\Form\UserEditForm\UserSalesCommissionPanel\Load
 */
trait UserSalesCommissionPanelLoaderCreateTrait
{
    protected ?UserSalesCommissionPanelLoader $userSalesCommissionPanelLoader = null;

    /**
     * @return UserSalesCommissionPanelLoader
     */
    protected function createUserSalesCommissionPanelLoader(): UserSalesCommissionPanelLoader
    {
        return $this->userSalesCommissionPanelLoader ?: UserSalesCommissionPanelLoader::new();
    }

    /**
     * @param UserSalesCommissionPanelLoader $userSalesCommissionPanelLoader
     * @return $this
     * @internal
     */
    public function setUserSalesCommissionPanelLoader(UserSalesCommissionPanelLoader $userSalesCommissionPanelLoader): static
    {
        $this->userSalesCommissionPanelLoader = $userSalesCommissionPanelLoader;
        return $this;
    }
}
