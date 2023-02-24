<?php
/**
 * SAM-8049: Admin User Edit - restricted users should not be accessible by direct link
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Access\UserManagement;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class UserManagementAccessCheckResult
 * @package Sam\User\Access\UserManagement
 */
class UserManagementAccessCheckResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_ANONYMOUS_EDITOR_USER_CANNOT_ACCESS_ANY_USER = 1;
    public const ERR_EDITOR_USER_ABSENT = 2;
    public const ERR_TARGET_USER_ABSENT = 3;
    public const ERR_EDITOR_USER_WITHOUT_MANAGE_USERS_PRIVILEGE_CANNOT_ACCESS_ANY_USER = 4;
    public const OK_NEW_USER_ALLOWED = 5;

    public const OK_MULTIPLE_TENANT_CROSS_DOMAIN_ADMIN_ON_MAIN_DOMAIN_CAN_ACCESS_ANY_TARGET_USER = 10;
    public const OK_MULTIPLE_TENANT_CROSS_DOMAIN_ADMIN_ON_PORTAL_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_DIRECT_ACCOUNT_OF_THIS_PORTAL = 11;
    public const OK_MULTIPLE_TENANT_CROSS_DOMAIN_ADMIN_ON_PORTAL_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_THIS_PORTAL_WHEN_SHARE_USER_INFO_IS_EDIT = 12;
    public const OK_MULTIPLE_TENANT_CROSS_DOMAIN_ADMIN_ON_PORTAL_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_THIS_PORTAL_WHEN_SHARE_USER_INFO_IS_VIEW = 13;
    public const ERR_MULTIPLE_TENANT_CROSS_DOMAIN_ADMIN_ON_PORTAL_DOMAIN_CANNOT_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_THIS_PORTAL_WHEN_SHARE_USER_INFO_IS_NONE = 14;
    public const ERR_MULTIPLE_TENANT_CROSS_DOMAIN_ADMIN_ON_PORTAL_DOMAIN_CANNOT_ACCESS_TARGET_USER_WITHOUT_DIRECT_AND_COLLATERAL_ACCOUNT_OF_THIS_PORTAL = 15;

    public const OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_MAIN_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_DIRECT_ACCOUNT_OF_MAIN_DOMAIN = 21;
    public const OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_MAIN_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_MAIN_DOMAIN_WHEN_SHARE_USER_INFO_IS_EDIT = 22;
    public const OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_MAIN_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_MAIN_DOMAIN_WHEN_SHARE_USER_INFO_IS_VIEW = 23;
    public const OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_MAIN_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_MAIN_DOMAIN_WHEN_SHARE_USER_INFO_IS_NONE = 24;
    public const OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_MAIN_DOMAIN_CAN_ACCESS_TARGET_USER_WITHOUT_DIRECT_AND_COLLATERAL_ACCOUNT_TO_MAIN_DOMAIN = 25;

    public const OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_PORTAL_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_DIRECT_ACCOUNT_OF_THIS_PORTAL = 30;
    public const OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_PORTAL_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_THIS_PORTAL_WHEN_SHARE_USER_INFO_IS_EDIT = 31;
    public const OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_PORTAL_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_THIS_PORTAL_WHEN_SHARE_USER_INFO_IS_VIEW = 32;
    public const ERR_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_PORTAL_DOMAIN_CANNOT_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_THIS_PORTAL_WHEN_SHARE_USER_INFO_IS_NONE = 33;
    public const ERR_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_PORTAL_DOMAIN_CANNOT_ACCESS_TARGET_USER_WITHOUT_DIRECT_AND_COLLATERAL_ACCOUNT_OF_THIS_PORTAL = 34;

    public const ERR_SINGLE_TENANT_PORTAL_DOMAIN_TARGET_USER_NOT_AVAILABLE = 42;
    public const OK_SINGLE_TENANT_MAIN_DOMAIN_ADMIN_CAN_ACCESS_ANY_USER_OF_MAIN_DOMAIN = 43;

    // @formatter:off
    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_ANONYMOUS_EDITOR_USER_CANNOT_ACCESS_ANY_USER => 'Anonymous editor user cannot access any user',
        self::ERR_EDITOR_USER_ABSENT => 'Editor user absent',
        self::ERR_TARGET_USER_ABSENT => 'Target user absent',
        self::ERR_EDITOR_USER_WITHOUT_MANAGE_USERS_PRIVILEGE_CANNOT_ACCESS_ANY_USER
            => 'Editor user without "Manage Users" privilege cannot access any user',
        self::ERR_MULTIPLE_TENANT_CROSS_DOMAIN_ADMIN_ON_PORTAL_DOMAIN_CANNOT_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_THIS_PORTAL_WHEN_SHARE_USER_INFO_IS_NONE
            => 'Cross-domain admin, who visits portal domain, cannot access target user with collateral account relation of this portal, when "Share User Info" is "None"',
        self::ERR_MULTIPLE_TENANT_CROSS_DOMAIN_ADMIN_ON_PORTAL_DOMAIN_CANNOT_ACCESS_TARGET_USER_WITHOUT_DIRECT_AND_COLLATERAL_ACCOUNT_OF_THIS_PORTAL
            => 'Cross-domain admin, who visits portal domain, cannot access target user without direct and collateral account relation to this portal domain',
        self::ERR_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_PORTAL_DOMAIN_CANNOT_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_THIS_PORTAL_WHEN_SHARE_USER_INFO_IS_NONE
            => 'Regular admin, who visits own portal domain, cannot access target user with collateral account of this portal, when "Share User Info" is "None"',
        self::ERR_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_PORTAL_DOMAIN_CANNOT_ACCESS_TARGET_USER_WITHOUT_DIRECT_AND_COLLATERAL_ACCOUNT_OF_THIS_PORTAL
            => 'Regular admin, who visits own portal domain, cannot access target user without direct and collateral account relation to this portal domain',
        self::ERR_SINGLE_TENANT_PORTAL_DOMAIN_TARGET_USER_NOT_AVAILABLE => 'Target user of portal domain is not available in Single-tenant installation',
    ];

    /** @var string[] */
    protected const SUCCESS_MESSAGES = [
        self::OK_NEW_USER_ALLOWED => 'Creation of new user is allowed',
        self::OK_MULTIPLE_TENANT_CROSS_DOMAIN_ADMIN_ON_MAIN_DOMAIN_CAN_ACCESS_ANY_TARGET_USER
            => 'Cross-domain admin, who visits main domain, can access any user',
        self::OK_MULTIPLE_TENANT_CROSS_DOMAIN_ADMIN_ON_PORTAL_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_DIRECT_ACCOUNT_OF_THIS_PORTAL
            => 'Cross-domain admin, who visits portal domain, can access user with direct account relation to this portal domain',
        self::OK_MULTIPLE_TENANT_CROSS_DOMAIN_ADMIN_ON_PORTAL_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_THIS_PORTAL_WHEN_SHARE_USER_INFO_IS_EDIT
            => 'Cross-domain admin, who visits portal domain, can access user with collateral account relation to this portal domain, when "Share User Info" is "Edit"',
        self::OK_MULTIPLE_TENANT_CROSS_DOMAIN_ADMIN_ON_PORTAL_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_THIS_PORTAL_WHEN_SHARE_USER_INFO_IS_VIEW
            => 'Cross-domain admin, who visits portal domain, can access user with collateral account relation to this portal domain, when "Share User Info" is "View"',
        self::OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_MAIN_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_DIRECT_ACCOUNT_OF_MAIN_DOMAIN
            => 'Regular admin, who visits own main domain, can access user with direct account relation to main domain',
        self::OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_MAIN_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_MAIN_DOMAIN_WHEN_SHARE_USER_INFO_IS_EDIT
            => 'Regular admin, who visits own main domain, can access user with collateral account relation to main domain, when "Share User Info" is "Edit"',
        self::OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_MAIN_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_MAIN_DOMAIN_WHEN_SHARE_USER_INFO_IS_VIEW
            => 'Regular admin, who visits own main domain, can access user with collateral account relation to main domain, when "Share User Info" is "View"',
        self::OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_MAIN_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_MAIN_DOMAIN_WHEN_SHARE_USER_INFO_IS_NONE
            => 'Regular admin, who visits own main domain, can access user with collateral account relation to main domain, when "Share User Info" is "None"',
        self::OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_MAIN_DOMAIN_CAN_ACCESS_TARGET_USER_WITHOUT_DIRECT_AND_COLLATERAL_ACCOUNT_TO_MAIN_DOMAIN
            => 'Regular admin, who visits own main domain, can access user without direct and collateral account relation to main domain',
        self::OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_PORTAL_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_DIRECT_ACCOUNT_OF_THIS_PORTAL
            => 'Regular admin, who visits own portal domain, can access user with direct account relation to this portal domain',
        self::OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_PORTAL_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_THIS_PORTAL_WHEN_SHARE_USER_INFO_IS_EDIT
            => 'Regular admin, who visits own portal domain, can access user with collateral account relation to this portal, when "Share User Info" is "Edit"',
        self::OK_MULTIPLE_TENANT_REGULAR_ADMIN_ON_OWN_PORTAL_DOMAIN_CAN_ACCESS_TARGET_USER_WITH_COLLATERAL_ACCOUNT_OF_THIS_PORTAL_WHEN_SHARE_USER_INFO_IS_VIEW
            => 'Regular admin, who visits own portal domain, can access user with collateral account relation to this portal, when "Share User Info" is "View"',
        self::OK_SINGLE_TENANT_MAIN_DOMAIN_ADMIN_CAN_ACCESS_ANY_USER_OF_MAIN_DOMAIN
            => 'Regular admin, who visits main domain at Single-tenant installation, can access any user of main domain',
    ];
    // @formatter:on

    protected bool $isEditable = false;
    protected bool $isViewable = false;
    protected ?int $targetUserid;
    protected ?int $editorUserId;
    protected int $systemAccountId;

    // --- Constructors ---

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(?int $targetUserId, ?int $editorUserId, int $systemAccountId): static
    {
        $this->targetUserid = $targetUserId;
        $this->editorUserId = $editorUserId;
        $this->systemAccountId = $systemAccountId;
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES, self::SUCCESS_MESSAGES);
        return $this;
    }

    // --- Output status ---

    public function isEditable(): bool
    {
        return $this->isEditable;
    }

    public function isViewable(): bool
    {
        return $this->isViewable;
    }

    public function targetUserId(): ?int
    {
        return $this->targetUserid;
    }

    public function editorUserId(): ?int
    {
        return $this->editorUserId;
    }

    public function systemAccountId(): int
    {
        return $this->systemAccountId;
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstErrorCode();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function successCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstSuccessCode();
    }

    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    public function logData(): array
    {
        $logData = [
            'editable' => $this->isEditable,
            'viewable' => $this->isViewable,
            'editor u' => $this->editorUserId,
            'target u' => $this->targetUserid,
            'system acc' => $this->systemAccountId,
        ];
        if ($this->hasError()) {
            $logData['error code'] = $this->errorCode();
            $logData['error message'] = $this->errorMessage();
        }
        if ($this->hasSuccess()) {
            $logData['success code'] = $this->successCode();
            $logData['success message'] = $this->successMessage();
        }
        return $logData;
    }

    // --- Mutate ---

    public function addError(int $code): static
    {
        $this->enableAvailableActions(false, false);
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addEditViewSuccess(int $code): static
    {
        $this->enableAvailableActions(true, true);
        $this->getResultStatusCollector()->addSuccess($code);
        return $this;
    }

    public function addOnlyViewSuccess(int $code): static
    {
        $this->enableAvailableActions(false, true);
        $this->getResultStatusCollector()->addSuccess($code);
        return $this;
    }

    // --- Internal ---

    protected function enableAvailableActions(bool $isEditable, bool $isViewable): void
    {
        $this->isEditable = $isEditable;
        $this->isViewable = $isViewable;
    }
}
