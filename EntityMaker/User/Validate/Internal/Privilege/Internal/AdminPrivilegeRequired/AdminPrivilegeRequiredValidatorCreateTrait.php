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

namespace Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\AdminPrivilegeRequired;

/**
 * Trait AdminPrivilegeRequiredValidatorCreateTrait
 * @package Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\AdminPrivilegeRequired
 */
trait AdminPrivilegeRequiredValidatorCreateTrait
{
    protected ?AdminPrivilegeRequiredValidator $adminPrivilegeRequiredValidator = null;

    /**
     * @return AdminPrivilegeRequiredValidator
     */
    protected function createAdminPrivilegeRequiredValidator(): AdminPrivilegeRequiredValidator
    {
        return $this->adminPrivilegeRequiredValidator ?: AdminPrivilegeRequiredValidator::new();
    }

    /**
     * @param AdminPrivilegeRequiredValidator $adminPrivilegeRequiredValidator
     * @return static
     * @internal
     */
    public function setAdminPrivilegeRequiredValidator(AdminPrivilegeRequiredValidator $adminPrivilegeRequiredValidator): static
    {
        $this->adminPrivilegeRequiredValidator = $adminPrivilegeRequiredValidator;
        return $this;
    }
}
