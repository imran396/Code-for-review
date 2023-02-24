<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Download\UserCustomField\Internal;

/**
 * Trait UserCustomFieldDataExistenceCheckerCreateTrait
 * @package Sam\Application\Controller\Responsive\Download\Internal
 */
trait UserCustomFieldDataExistenceCheckerCreateTrait
{
    /**
     * @var UserCustomFieldDataExistenceChecker|null
     */
    protected ?UserCustomFieldDataExistenceChecker $userCustomFieldDataExistenceChecker = null;

    /**
     * @return UserCustomFieldDataExistenceChecker
     */
    protected function createUserCustomFieldDataExistenceChecker(): UserCustomFieldDataExistenceChecker
    {
        return $this->userCustomFieldDataExistenceChecker ?: UserCustomFieldDataExistenceChecker::new();
    }

    /**
     * @param UserCustomFieldDataExistenceChecker $userCustomFieldDataExistenceChecker
     * @return static
     * @internal
     */
    public function setUserCustomFieldDataExistenceChecker(UserCustomFieldDataExistenceChecker $userCustomFieldDataExistenceChecker): static
    {
        $this->userCustomFieldDataExistenceChecker = $userCustomFieldDataExistenceChecker;
        return $this;
    }
}
