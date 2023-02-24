<?php
/**
 * SAM-6895: Apply hierarchical options rules for "Simplified Signup"
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Ivan Zgoniaiko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\User;

/**
 * Trait UserSettingCheckerAwareTrait
 * @package Sam\Settings\User
 */
trait UserSettingCheckerCreateTrait
{
    /**
     * @var UserSettingChecker|null
     */
    protected ?UserSettingChecker $userSettingChecker = null;

    /**
     * @return UserSettingChecker
     */
    protected function createUserSettingChecker(): UserSettingChecker
    {
        return $this->userSettingChecker ?: UserSettingChecker::new();
    }

    /**
     * @param UserSettingChecker $userSettingChecker
     * @return $this
     * @internal
     */
    public function setUserSettingChecker(UserSettingChecker $userSettingChecker): static
    {
        $this->userSettingChecker = $userSettingChecker;
        return $this;
    }
}
