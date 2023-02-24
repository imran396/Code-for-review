<?php
/**
 * SAM-6795: Validations at controller layer for v3.5 - UserControllerValidator at admin site
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\User\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class UserControllerValidationResult
 * @package Sam\Application\Controller\Admn\User
 */
class UserControllerValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    // --- Outgoing values ---

    /**
     * Check user entity existence
     */
    public const ERR_TARGET_USER_NOT_FOUND_BY_ID = 1;
    /**
     * Check user availability (not deleted)
     */
    public const ERR_TARGET_USER_DELETED = 2;
    /**
     * Access denied. Admin has not enough privileges
     */
    public const ERR_EDITOR_USER_ABSENT_PRIVILEGE_FOR_MANAGE_USERS = 3;
    /**
     * Check user account
     */
    public const ERR_TARGET_USER_ACCOUNT_NOT_FOUND = 4;
    /**
     * Check access to target user
     */
    public const ERR_TARGET_USER_ACCESS_DENIED = 5;

    /** @var string[] */
    public const ERROR_MESSAGES = [
        self::ERR_TARGET_USER_NOT_FOUND_BY_ID => 'Target user not found by id',
        self::ERR_TARGET_USER_DELETED => 'Target user deleted',
        self::ERR_EDITOR_USER_ABSENT_PRIVILEGE_FOR_MANAGE_USERS => 'Editor user does not have "Manage Users" privilege',
        self::ERR_TARGET_USER_ACCOUNT_NOT_FOUND => 'Target user account not available',
        self::ERR_TARGET_USER_ACCESS_DENIED => 'Target user access denied',
    ];

    /**
     * WHen creating new user, no need in most of validations
     */
    public const OK_CREATE_NEW_USER = 10;
    /**
     * All validations completed successfully
     */
    public const OK_SUCCESS_VALIDATION = 11;

    /** @var string[] */
    public const SUCCESS_MESSAGES = [
        self::OK_CREATE_NEW_USER => 'Creating new user',
        self::OK_SUCCESS_VALIDATION => 'Successful validation',
    ];

    protected ?int $targetUserId;
    protected ?int $editorUserId;
    protected int $systemAccountId;

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

    // --- Mutate state ---

    public function addError(int $code, ?string $message = null, array $payload = []): static
    {
        $this->getResultStatusCollector()->addError($code, $message, $payload);
        return $this;
    }

    public function addSuccess(int $code): static
    {
        $this->getResultStatusCollector()->addSuccess($code);
        return $this;
    }

    // --- Outgoing results ---

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function successCodes(): array
    {
        return $this->getResultStatusCollector()->getSuccessCodes();
    }

    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    public function errorPayload(): array
    {
        $payloads = $this->getResultStatusCollector()->getErrorPayloads();
        return $payloads[0] ?? [];
    }

    public function logData(): array
    {
        $logData = [
            'editor u' => $this->editorUserId,
            'target u' => $this->targetUserId,
            'system acc' => $this->systemAccountId,
        ];
        $payload = $this->hasError() ? $this->errorPayload() : [];
        $logData = array_merge($logData, $payload);
        return $logData;
    }

    /**
     * @return bool
     */
    public function hasUnavailableError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(
            [self::ERR_TARGET_USER_DELETED, self::ERR_TARGET_USER_NOT_FOUND_BY_ID]
        );
    }
}
