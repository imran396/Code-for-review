<?php
/**
 * Change Password Updater Create Trait
 *
 * SAM-6022: Refactor change password form at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 27, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ChangePasswordForm\Save;

/**
 * Trait ChangePasswordUpdaterCreateTrait
 */
trait ChangePasswordUpdaterCreateTrait
{
    protected ?ChangePasswordUpdater $changePasswordUpdater = null;

    /**
     * @return ChangePasswordUpdater
     */
    protected function createChangePasswordUpdater(): ChangePasswordUpdater
    {
        $changePasswordUpdater = $this->changePasswordUpdater ?: ChangePasswordUpdater::new();
        return $changePasswordUpdater;
    }

    /**
     * @param ChangePasswordUpdater $changePasswordUpdater
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setChangePasswordUpdater(ChangePasswordUpdater $changePasswordUpdater): static
    {
        $this->changePasswordUpdater = $changePasswordUpdater;
        return $this;
    }
}
