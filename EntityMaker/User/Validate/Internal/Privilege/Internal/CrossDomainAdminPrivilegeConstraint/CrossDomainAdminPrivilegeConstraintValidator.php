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

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\ValueResolver;
use Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\CrossDomainAdminPrivilegeConstraint\CrossDomainAdminPrivilegeConstraintValidationResult as Result;
use Sam\EntityMaker\User\Validate\Internal\Privilege\PrivilegeValidationInput as Input;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class CrossDomainConstraintValidator
 * @package Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\CrossDomainAdminConstraint
 * @internal
 */
class CrossDomainAdminPrivilegeConstraintValidator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Checks if the cross-domain admin is linked to the main account
     *
     * @param Input $input
     * @param int $userDirectAccountId
     * @return CrossDomainAdminPrivilegeConstraintValidationResult
     */
    public function validate(Input $input, int $userDirectAccountId): Result
    {
        $result = Result::new()->construct();
        if (
            ValueResolver::new()->isTrue($input->crossDomain)
            && !$this->isMainAccount($userDirectAccountId)
        ) {
            $result->addError(Result::ERR_CROSS_DOMAIN_ADMIN_AT_PORTAL_ACCOUNT);
        }
        return $result;
    }

    protected function isMainAccount(int $accountId): bool
    {
        return $accountId === $this->cfg()->get('core->portal->mainAccountId');
    }
}
