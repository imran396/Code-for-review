<?php
/**
 * SAM-9520: Important Security user privilege issue
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\UserRole;

/**
 * Trait UserRoleValidatorCreateTrait
 * @package Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\Internal
 */
trait UserRoleValidatorCreateTrait
{
    protected ?UserRoleValidator $userRoleValidator = null;

    /**
     * @return UserRoleValidator
     */
    protected function createUserRoleValidator(): UserRoleValidator
    {
        return $this->userRoleValidator ?: UserRoleValidator::new();
    }

    /**
     * @param UserRoleValidator $userRoleValidator
     * @return static
     * @internal
     */
    public function setUserRoleValidator(UserRoleValidator $userRoleValidator): static
    {
        $this->userRoleValidator = $userRoleValidator;
        return $this;
    }
}
