<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\ApplyPayment\Single\Validate\Result;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementCheckPaymentApplyingValidationResult
 * @package Sam\Settlement\Check
 */
class SingleSettlementCheckPaymentApplyingValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_UNKNOWN_ID = 1;
    public const ERR_CHECK_NOT_FOUND = 2;
    public const ERR_ALREADY_PAYMENT_APPLIED = 3;
    public const ERR_ALREADY_VOIDED = 4;

    // --- Construct ---
    protected const ERROR_MESSAGES = [
        self::ERR_UNKNOWN_ID => 'Unknown check ID',
        self::ERR_CHECK_NOT_FOUND => 'Check not found',
        self::ERR_ALREADY_PAYMENT_APPLIED => 'Already applied as payment',
        self::ERR_ALREADY_VOIDED => 'Already voided',
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
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    // --- Query ---

    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessage(?string $glue = null): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasAlreadyAppliedPaymentError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_ALREADY_PAYMENT_APPLIED]);
    }

    public function hasAlreadyVoidedError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_ALREADY_VOIDED]);
    }

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }

    /**
     * @return ResultStatus[]
     */
    public function getErrorStatuses(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }

    public function logData(): array
    {
        if ($this->hasError()) {
            return ['error' => $this->errorMessage()];
        }
        return [];
    }
}
