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

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\ValueResolver;
use Sam\EntityMaker\User\Validate\Internal\Privilege\PrivilegeValidationInput as Input;
use Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\AdminPrivilegeRequired\AdminPrivilegeRequiredValidationResult as Result;

/**
 * Class AdminPrivilegeRequiredValidator
 * @package Sam\EntityMaker\User\Validate\Internal\Privilege
 */
class AdminPrivilegeRequiredValidator extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Checks if admin privilege is set in case when at least one sub privilege is enabled
     *
     * @param Input $input
     * @return AdminPrivilegeRequiredValidationResult
     */
    public function validate(Input $input): Result
    {
        $result = Result::new()->construct();
        if (
            ValueResolver::new()->isTrue($input->admin)
            && !$this->isAdminSubPrivilegesSelected($input)
        ) {
            $result->addError(Result::ERR_NO_ONE_ADMIN_PRIVILEGES_SELECTED);
        }
        return $result;
    }

    protected function isAdminSubPrivilegesSelected(Input $input): bool
    {
        $valueResolver = ValueResolver::new();
        if ($valueResolver->isTrue($input->manageAuctions)) {
            return true;
        }
        if ($valueResolver->isTrue($input->manageInventory)) {
            return true;
        }
        if ($valueResolver->isTrue($input->manageUsers)) {
            return true;
        }
        if ($valueResolver->isTrue($input->manageInvoices)) {
            return true;
        }
        if ($valueResolver->isTrue($input->manageSettlements)) {
            return true;
        }
        if ($valueResolver->isTrue($input->manageSettings)) {
            return true;
        }
        if ($valueResolver->isTrue($input->manageCcInfo)) {
            return true;
        }
        if ($valueResolver->isTrue($input->salesStaff)) {
            return true;
        }
        if ($valueResolver->isTrue($input->manageReports)) {
            return true;
        }
        if ($valueResolver->isTrue($input->crossDomain)) {
            return true;
        }
        return false;
    }
}
