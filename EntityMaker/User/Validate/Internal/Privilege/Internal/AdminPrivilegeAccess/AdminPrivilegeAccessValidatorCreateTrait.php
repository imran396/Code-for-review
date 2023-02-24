<?php
/**
 * SAM-9520: Important Security user privilege issue
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\AdminPrivilegeAccess;

/**
 * Trait AdminPrivilegeAccessValidatorCreateTrait
 * @package Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\AdminPrivilegeAccess
 */
trait AdminPrivilegeAccessValidatorCreateTrait
{
    protected ?AdminPrivilegeAccessValidator $adminPrivilegeAccessValidator = null;

    /**
     * @return AdminPrivilegeAccessValidator
     */
    protected function createAdminPrivilegeAccessValidator(): AdminPrivilegeAccessValidator
    {
        return $this->adminPrivilegeAccessValidator ?: AdminPrivilegeAccessValidator::new();
    }

    /**
     * @param AdminPrivilegeAccessValidator $adminPrivilegeAccessValidator
     * @return static
     * @internal
     */
    public function setAdminPrivilegeAccessValidator(AdminPrivilegeAccessValidator $adminPrivilegeAccessValidator): static
    {
        $this->adminPrivilegeAccessValidator = $adminPrivilegeAccessValidator;
        return $this;
    }
}
