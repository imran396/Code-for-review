<?php
/**
 * SAM-9666: Retire Cross-domain privilege for portal admins
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\CrossDomainAdminPrivilegeConstraint;

/**
 * Trait CrossDomainAdminPrivilegeConstraintValidatorCreateTrait
 * @package Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\CrossDomainAdminPrivilegeConstraint
 */
trait CrossDomainAdminPrivilegeConstraintValidatorCreateTrait
{
    protected ?CrossDomainAdminPrivilegeConstraintValidator $crossDomainAdminPrivilegeConstraintValidator = null;

    /**
     * @return CrossDomainAdminPrivilegeConstraintValidator
     */
    protected function createCrossDomainAdminPrivilegeConstraintValidator(): CrossDomainAdminPrivilegeConstraintValidator
    {
        return $this->crossDomainAdminPrivilegeConstraintValidator ?: CrossDomainAdminPrivilegeConstraintValidator::new();
    }

    /**
     * @param CrossDomainAdminPrivilegeConstraintValidator $crossDomainAdminPrivilegeConstraintValidator
     * @return static
     * @internal
     */
    public function setCrossDomainAdminPrivilegeConstraintValidator(CrossDomainAdminPrivilegeConstraintValidator $crossDomainAdminPrivilegeConstraintValidator): static
    {
        $this->crossDomainAdminPrivilegeConstraintValidator = $crossDomainAdminPrivilegeConstraintValidator;
        return $this;
    }
}
