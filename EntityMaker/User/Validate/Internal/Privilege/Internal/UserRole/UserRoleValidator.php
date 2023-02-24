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

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\ValueResolver;
use Sam\EntityMaker\User\Validate\Internal\Privilege\PrivilegeValidationInput as Input;
use Sam\User\Privilege\Access\Role\UserRoleAccessCheckerCreateTrait;
use Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\UserRole\UserRoleValidationResult as Result;

/**
 * Class UserRoleValidator
 * @package Sam\EntityMaker\User\Validate\Internal\Privilege
 * @internal
 */
class UserRoleValidator extends CustomizableClass
{
    use UserRoleAccessCheckerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Checks if user roles can be set and if the editor has permission to perform this action.
     * Admin can't have a consignor or bidder role
     *
     * @param Input $input
     * @return UserRoleValidationResult
     */
    public function validate(Input $input): Result
    {
        $result = Result::new()->construct();
        if (!$this->isApplicable($input)) {
            return $result;
        }

        $isSetAdmin = $input->isBooleanValueSet('admin');
        $isSetConsignorOrBidder = $input->isBooleanValueSet('bidder')
            || $input->isBooleanValueSet('consignor');

        $canEditAdmin = $this->createUserRoleAccessChecker()->canEditAdminRole(
            $input->editorUserId,
            $input->id,
            ValueResolver::new()->isTrue($input->bidder),
            ValueResolver::new()->isTrue($input->consignor),
            $input->systemAccountId
        );
        $canEditConsignorOrBidder = $this->createUserRoleAccessChecker()->canEditConsignorOrBidderRole(
            $input->editorUserId,
            $input->id,
            ValueResolver::new()->isTrue($input->admin),
            $input->systemAccountId
        );

        if (
            $isSetAdmin
            && $isSetConsignorOrBidder
            && !$canEditAdmin
            && !$canEditConsignorOrBidder
        ) {
            return $result->addError(Result::ERR_ADMIN_AND_BIDDER_CONSIGNOR_PRIVILEGE_TOGETHER_NOT_ALLOWED);
        }

        if (
            $isSetAdmin
            && !$canEditAdmin
        ) {
            return $result->addError(Result::ERR_ADMIN_PRIVILEGES_IS_NOT_EDITABLE);
        }

        if (
            $isSetConsignorOrBidder
            && !$canEditConsignorOrBidder
        ) {
            return $result->addError(Result::ERR_BIDDER_AND_CONSIGNOR_PRIVILEGES_IS_NOT_EDITABLE);
        }

        return $result;
    }

    protected function isApplicable(Input $input): bool
    {
        $isApplicable = $input->editorUserId
            && (
                $input->isBooleanValueSet('admin')
                || $input->isBooleanValueSet('bidder')
                || $input->isBooleanValueSet('consignor')
            );
        return $isApplicable;
    }
}
