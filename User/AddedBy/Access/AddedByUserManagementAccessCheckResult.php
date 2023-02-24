<?php
/**
 * SAM-6928: Sales staff user assignment and filtering control adjustments at the "User Edit" and the "Sales Report" pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\AddedBy\Access;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AddedByUserManagementAccessCheckResult
 * @package Sam\User\AddedBy\Access
 */
class AddedByUserManagementAccessCheckResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_TARGET_USER_NOT_CONSIGNOR = 1;
    public const ERR_TARGET_USER_NOT_VIEWABLE = 2;
    public const ERR_TARGET_USER_ACCOUNT_RESTRICTION = 3;
    public const ERR_NEW_TARGET_USER_DIRECT_ACCOUNT_UNKNOWN = 4;
    public const ERR_ACTUAL_ADDED_BY_NOT_LEGITIMATE_AND_NOT_VIEWABLE_AGENT_AND_TARGET_USER_EDIT_DENIED = 5;
    public const ERR_ACTUAL_ADDED_BY_NOT_LEGITIMATE_BUT_IS_VIEWABLE_AGENT_ALTHOUGH_TARGET_USER_EDIT_DENIED = 6;

    public const OK_ALLOW_IN_SINGLE_TENANT_INSTALLATION = 10;
    public const OK_ACTUAL_ADDED_BY_EMPTY = 11;
    public const OK_ALLOW_BY_ACCOUNT_RESTRICTION = 12;
    public const OK_ACTUAL_ADDED_BY_NOT_LEGITIMATE_AGENT_AND_EDIT_ALLOWED = 13;

    protected const ERROR_MESSAGES = [
        self::ERR_TARGET_USER_NOT_CONSIGNOR => 'Cannot edit target user without consignor role',
        self::ERR_TARGET_USER_NOT_VIEWABLE => 'Target user is not viewable in read-only mode',
        self::ERR_TARGET_USER_ACCOUNT_RESTRICTION => 'Cannot edit according to account restriction of target user',
        self::ERR_NEW_TARGET_USER_DIRECT_ACCOUNT_UNKNOWN => 'Unknown account restriction, when creating new user by cross-domain admin at main domain',
        self::ERR_ACTUAL_ADDED_BY_NOT_LEGITIMATE_AND_NOT_VIEWABLE_AGENT_AND_TARGET_USER_EDIT_DENIED => 'Actual assignment is not legitimate sales staff agent, who is not viewable, and target user is not editable',
        self::ERR_ACTUAL_ADDED_BY_NOT_LEGITIMATE_BUT_IS_VIEWABLE_AGENT_ALTHOUGH_TARGET_USER_EDIT_DENIED => 'Actual assignment is not legitimate sales staff agent, but he is viewable, although target user is not editable',
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_ALLOW_IN_SINGLE_TENANT_INSTALLATION => 'Allow to modify in single-tenant installation',
        self::OK_ACTUAL_ADDED_BY_EMPTY => 'Allow to modify, when actual value is undefined',
        self::OK_ALLOW_BY_ACCOUNT_RESTRICTION => 'Allow to modify according account restriction',
        self::OK_ACTUAL_ADDED_BY_NOT_LEGITIMATE_AGENT_AND_EDIT_ALLOWED => 'Allow to modify, because actual assignment is not legitimate sales staff agent and target user is editable',
    ];

    /**
     * "Added By" field editable
     */
    public bool $isEditable = false;
    /**
     * "Added By" info available in read-only mode
     */
    public bool $isViewable = false;
    public ?int $targetUserId;
    public ?int $editorUserId;
    public int $systemAccountId;
    public ?int $actualAddedByUserId = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $targetUserId
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @return $this
     */
    public function construct(?int $targetUserId, ?int $editorUserId, int $systemAccountId): static
    {
        $this->targetUserId = $targetUserId;
        $this->editorUserId = $editorUserId;
        $this->systemAccountId = $systemAccountId;
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES, self::SUCCESS_MESSAGES);
        return $this;
    }

    // Mutation methods

    public function addError(int $code, bool $isViewable = false): static
    {
        $this->getResultStatusCollector()->addError($code);
        $this->isViewable = $isViewable;
        return $this;
    }

    public function addSuccess(int $code, bool $isEditable = true, bool $isViewable = true): static
    {
        $this->getResultStatusCollector()->addSuccess($code);
        $this->isEditable = $isEditable;
        $this->isViewable = $isViewable;
        return $this;
    }

    public function setActualAddedByUserId(?int $addedByUserId): static
    {
        $this->actualAddedByUserId = $addedByUserId;
        return $this;
    }

    // Outgoing results

    public function isEditable(): bool
    {
        return $this->isEditable;
    }

    public function isViewable(): bool
    {
        return $this->isViewable;
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstErrorCode();
    }

    public function hasNewTargetUserError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_NEW_TARGET_USER_DIRECT_ACCOUNT_UNKNOWN);
    }

    public function hasAvailabilityError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(
            [
                self::ERR_TARGET_USER_ACCOUNT_RESTRICTION,
                self::ERR_ACTUAL_ADDED_BY_NOT_LEGITIMATE_AND_NOT_VIEWABLE_AGENT_AND_TARGET_USER_EDIT_DENIED
            ]
        );
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
        $logData = [];
        $logData['editable'] = $this->isEditable;
        $logData['viewable'] = $this->isViewable;
        $logData += [
            'target u' => $this->targetUserId,
            'editor u' => $this->editorUserId,
            'system acc' => $this->systemAccountId,
        ];
        if ($this->actualAddedByUserId) {
            $logData['actual added by u'] = $this->actualAddedByUserId;
        }
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
}
