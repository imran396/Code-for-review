<?php
/**
 * This mediator service integrate internal privilege validation services with UserMakerValidator.
 *
 * SAM-9520: Important Security user privilege issue
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Validate\Internal\Privilege;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Validate\Constants\ResultCode;
use Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\AdminPrivilegeAccess\AdminPrivilegeAccessValidationResult;
use Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\AdminPrivilegeAccess\AdminPrivilegeAccessValidatorCreateTrait;
use Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\AdminPrivilegeRequired\AdminPrivilegeRequiredValidationResult;
use Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\AdminPrivilegeRequired\AdminPrivilegeRequiredValidatorCreateTrait;
use Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\CrossDomainAdminPrivilegeConstraint\CrossDomainAdminPrivilegeConstraintValidationResult;
use Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\CrossDomainAdminPrivilegeConstraint\CrossDomainAdminPrivilegeConstraintValidatorCreateTrait;
use Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\UserRole\UserRoleValidationResult;
use Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\UserRole\UserRoleValidatorCreateTrait;
use Sam\EntityMaker\User\Validate\Internal\Privilege\PrivilegeValidationInput as Input;
use Sam\EntityMaker\User\Validate\UserMakerValidator;

/**
 * Class ValidatePrivilegeIntegrator
 * @package Sam\EntityMaker\User\Validate\Internal\Privilege\Internal\Integrate
 */
class PrivilegeValidationIntegrator extends CustomizableClass
{
    use AdminPrivilegeAccessValidatorCreateTrait;
    use AdminPrivilegeRequiredValidatorCreateTrait;
    use CrossDomainAdminPrivilegeConstraintValidatorCreateTrait;
    use UserRoleValidatorCreateTrait;

    /** @var int[] */
    protected const MAP_USER_ROLE_VALIDATION_ERROR_CODES = [
        UserRoleValidationResult::ERR_ADMIN_AND_BIDDER_CONSIGNOR_PRIVILEGE_TOGETHER_NOT_ALLOWED => ResultCode::ADMIN_AND_BIDDER_CONSIGNOR_PRIVILEGE_TOGETHER_NOT_ALLOWED,
        UserRoleValidationResult::ERR_ADMIN_PRIVILEGES_IS_NOT_EDITABLE => ResultCode::ADMIN_PRIVILEGES_IS_NOT_EDITABLE,
        UserRoleValidationResult::ERR_BIDDER_AND_CONSIGNOR_PRIVILEGES_IS_NOT_EDITABLE => ResultCode::BIDDER_AND_CONSIGNOR_PRIVILEGES_IS_NOT_EDITABLE,
    ];

    /** @var int[] */
    protected const MAP_ADMIN_PRIVILEGE_REQUIRED_VALIDATION_ERROR_CODES = [
        AdminPrivilegeRequiredValidationResult::ERR_NO_ONE_ADMIN_PRIVILEGES_SELECTED => ResultCode::ADMIN_NO_SUB_PRIVILEGES_SELECTED,
    ];

    /** @var int[] */
    protected const MAP_CROSS_DOMAIN_ADMIN_PRIVILEGE_CONSTRAINT_VALIDATION_ERROR_CODES = [
        CrossDomainAdminPrivilegeConstraintValidationResult::ERR_CROSS_DOMAIN_ADMIN_AT_PORTAL_ACCOUNT => ResultCode::CROSS_DOMAIN_ADMIN_AT_PORTAL_ACCOUNT_NOT_APPLICABLE
    ];

    /** @var int[] */
    protected const MAP_ADMIN_PRIVILEGE_ACCESS_VALIDATION_ERROR_CODES = [
        AdminPrivilegeAccessValidationResult::ERR_MANAGE_AUCTIONS_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::MANAGE_AUCTIONS_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_MANAGE_INVENTORY_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::MANAGE_INVENTORY_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_MANAGE_USERS_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::MANAGE_USERS_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_MANAGE_INVOICES_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::MANAGE_INVOICES_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_MANAGE_SETTLEMENTS_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::MANAGE_SETTLEMENTS_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_MANAGE_SETTINGS_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::MANAGE_SETTINGS_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_MANAGE_CC_INFO_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::MANAGE_CC_INFO_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SALES_STAFF_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SALES_STAFF_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_MANAGE_REPORTS_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::MANAGE_REPORTS_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUPERADMIN_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUPERADMIN_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_AUCTION_MANAGE_ALL_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_AUCTION_MANAGE_ALL_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_AUCTION_DELETE_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_AUCTION_DELETE_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_AUCTION_ARCHIVE_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_AUCTION_ARCHIVE_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_AUCTION_RESET_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_AUCTION_RESET_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_AUCTION_INFORMATION_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_AUCTION_INFORMATION_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_AUCTION_PUBLISH_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_AUCTION_PUBLISH_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_AUCTION_LOT_LIST_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_AUCTION_LOT_LIST_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_AUCTION_AVAILABLE_LOT_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_AUCTION_AVAILABLE_LOT_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_AUCTION_BIDDER_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_AUCTION_BIDDER_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_AUCTION_REMAINING_USER_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_AUCTION_REMAINING_USER_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_AUCTION_RUN_LIVE_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_AUCTION_RUN_LIVE_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_AUCTION_AUCTIONEER_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_AUCTION_AUCTIONEER_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_AUCTION_PROJECTOR_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_AUCTION_PROJECTOR_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_AUCTION_BID_INCREMENT_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_AUCTION_BID_INCREMENT_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_AUCTION_BUYER_PREMIUM_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_AUCTION_BUYER_PREMIUM_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_AUCTION_PERMISSION_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_AUCTION_PERMISSION_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_USER_BULK_EXPORT_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_USER_BULK_EXPORT_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_USER_PASSWORD_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_USER_PASSWORD_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_USER_PRIVILEGE_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_USER_PRIVILEGE_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_USER_DELETE_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_USER_DELETE_PRIVILEGE_IS_NOT_EDITABLE,
        AdminPrivilegeAccessValidationResult::ERR_SUB_AUCTION_CREATE_BIDDER_PRIVILEGE_IS_NOT_EDITABLE => ResultCode::SUB_AUCTION_CREATE_BIDDER_PRIVILEGE_IS_NOT_EDITABLE,
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate privileges assignment for all user roles (admin, bidder, consignor)
     * @param UserMakerValidator $userMakerValidator
     * @param int $userDirectAccountId
     */
    public function validate(UserMakerValidator $userMakerValidator, int $userDirectAccountId): void
    {
        $input = Input::new()->fromMakerDto(
            $userMakerValidator->getInputDto(),
            $userMakerValidator->getConfigDto()
        );
        $errorMessages = [];

        $userRoleValidationResult = $this->createUserRoleValidator()->validate($input);
        if ($userRoleValidationResult->hasError()) {
            foreach ($userRoleValidationResult->errorCodes() as $errorCode) {
                $resultCode = self::MAP_USER_ROLE_VALIDATION_ERROR_CODES[$errorCode];
                $userMakerValidator->addError($resultCode);
            }
            $errorMessages += $userRoleValidationResult->errorMessages();
        }

        $adminPrivilegeRequiredValidationResult = $this->createAdminPrivilegeRequiredValidator()->validate($input);
        if ($adminPrivilegeRequiredValidationResult->hasError()) {
            foreach ($adminPrivilegeRequiredValidationResult->errorCodes() as $errorCode) {
                $resultCode = self::MAP_ADMIN_PRIVILEGE_REQUIRED_VALIDATION_ERROR_CODES[$errorCode];
                $userMakerValidator->addError($resultCode);
            }
            $errorMessages += $adminPrivilegeRequiredValidationResult->errorMessages();
        }

        $accessValidationResult = $this->createAdminPrivilegeAccessValidator()->validate($input);
        if ($accessValidationResult->hasError()) {
            foreach ($accessValidationResult->errorCodes() as $errorCode) {
                $resultCode = self::MAP_ADMIN_PRIVILEGE_ACCESS_VALIDATION_ERROR_CODES[$errorCode];
                $userMakerValidator->addError($resultCode);
            }
            $errorMessages += $accessValidationResult->errorMessages();
        }

        $crossDomainAdminValidationResult = $this->createCrossDomainAdminPrivilegeConstraintValidator()->validate($input, $userDirectAccountId);
        if ($crossDomainAdminValidationResult->hasError()) {
            foreach ($accessValidationResult->errorCodes() as $errorCode) {
                $resultCode = self::MAP_CROSS_DOMAIN_ADMIN_PRIVILEGE_CONSTRAINT_VALIDATION_ERROR_CODES[$errorCode];
                $userMakerValidator->addError($resultCode);
            }
        }

        if ($errorMessages) {
            log_debug('Privilege assignment validation failed: ' . implode(', ', $errorMessages));
        }
    }
}
