<?php
/**
 * SAM-9177: User entity-maker - Account related issues for v3-4, v3-5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Validate\Internal\DirectAccount;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DirectAccountChangeValidationResult
 * @package Sam\EntityMaker\User\Validate\Internal\DirectAccount
 */
class DirectAccountChangeValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_ACCOUNT_CHANGE_DENIED = 1;
    public const ERR_NEW_USER_ACCOUNT_ON_SIGNUP_MUST_BE_MAIN = 2;
    public const ERR_ACCOUNT_ASSIGN_DENIED = 3;
    public const ERR_ACCOUNT_NOT_FOUND = 4;

    public const OK_ACCOUNT_CHANGE_NOT_REQUESTED = 11;
    public const OK_NEW_ACCOUNT_IS_EQUAL_TO_OLD_ACCOUNT = 12;
    public const OK_NEW_USER_ACCOUNT_ON_SIGNUP_IS_MAIN = 13;
    public const OK_CROSS_DOMAIN_ADMIN_CAN_CHANGE_ACCOUNT = 14;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_ACCOUNT_CHANGE_DENIED => 'Account change denied',
        self::ERR_ACCOUNT_ASSIGN_DENIED => 'Account assign denied',
        self::ERR_NEW_USER_ACCOUNT_ON_SIGNUP_MUST_BE_MAIN => 'New user must sign up with main account',
        self::ERR_ACCOUNT_NOT_FOUND => 'Account not found',
    ];

    /** @var string[] */
    protected const SUCCESS_MESSAGES = [
        self::OK_ACCOUNT_CHANGE_NOT_REQUESTED => 'Account change not requested',
        self::OK_NEW_ACCOUNT_IS_EQUAL_TO_OLD_ACCOUNT => 'New account is equal to old account',
        self::OK_NEW_USER_ACCOUNT_ON_SIGNUP_IS_MAIN => 'New user signs himself up with main account',
        self::OK_CROSS_DOMAIN_ADMIN_CAN_CHANGE_ACCOUNT => 'Cross-domain admin can change account',
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
     * @return $this
     */
    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES, self::SUCCESS_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addSuccess(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addSuccess($code, $message);
        return $this;
    }

    // --- Query ---

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

    public function hasAccountNotFoundError(): bool
    {
        return $this->errorCode() === self::ERR_ACCOUNT_NOT_FOUND;
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

    public function statusCode(): ?int
    {
        if ($this->hasSuccess()) {
            return $this->successCode();
        }
        if ($this->hasError()) {
            return $this->errorCode();
        }
        return null;
    }

    public function logData(): array
    {
        $logData = [];
        if ($this->hasError()) {
            $logData += [
                'error code' => $this->errorCode(),
                'error message' => $this->errorMessage()
            ];
        }
        if ($this->hasSuccess()) {
            $logData += [
                'success code' => $this->successCode(),
                'success message' => $this->successMessage()
            ];
        }
        return $logData;
    }
}
