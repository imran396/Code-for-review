<?php
/**
 * SAM-6606: Refactoring classes in the \MySearch namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 08, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Search\Query\Build\Helper\Internal;

/**
 * Trait UserAccessRoleProviderCreateTrait
 * @package Sam\Lot\Search\Query\Build\Helper\Internal
 * @internal
 */
trait UserAccessRoleProviderCreateTrait
{
    protected ?UserAccessRoleProvider $userAccessRoleProvider = null;

    /**
     * @return UserAccessRoleProvider
     */
    protected function createUserAccessRoleProvider(): UserAccessRoleProvider
    {
        return $this->userAccessRoleProvider ?: UserAccessRoleProvider::new();
    }

    /**
     * @param UserAccessRoleProvider $userAccessRoleProvider
     * @return static
     * @internal
     */
    public function setUserAccessRoleProvider(UserAccessRoleProvider $userAccessRoleProvider): static
    {
        $this->userAccessRoleProvider = $userAccessRoleProvider;
        return $this;
    }
}
