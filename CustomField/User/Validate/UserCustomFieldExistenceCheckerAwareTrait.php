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
 * @since           Oct 11, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Validate;

/**
 * Trait UserCustomFieldExistenceCheckerAwareTrait
 * @package Sam\CustomField\User\Validate
 */
trait UserCustomFieldExistenceCheckerAwareTrait
{
    /**
     * @var UserCustomFieldExistenceChecker|null
     */
    protected ?UserCustomFieldExistenceChecker $userCustomFieldExistenceChecker = null;

    /**
     * @return UserCustomFieldExistenceChecker
     */
    protected function getUserCustomFieldExistenceChecker(): UserCustomFieldExistenceChecker
    {
        if ($this->userCustomFieldExistenceChecker === null) {
            $this->userCustomFieldExistenceChecker = UserCustomFieldExistenceChecker::new();
        }
        return $this->userCustomFieldExistenceChecker;
    }

    /**
     * @param UserCustomFieldExistenceChecker $userCustomFieldExistenceChecker
     * @return static
     * @internal
     */
    public function setUserCustomFieldExistenceChecker(UserCustomFieldExistenceChecker $userCustomFieldExistenceChecker): static
    {
        $this->userCustomFieldExistenceChecker = $userCustomFieldExistenceChecker;
        return $this;
    }
}
