<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\MarkVoided\Single\Validate\Result;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SingleSettlementCheckVoidingValidationResult
 * @package Sam\Settlement\Check
 */
class SingleSettlementCheckVoidingValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_UNKNOWN_ID = 1;
    public const ERR_CHECK_NOT_FOUND = 2;
    public const ERR_ALREADY_VOIDED_ON = 3;

    public const WARN_PAYMENT_APPLIED = 11;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_UNKNOWN_ID => 'Unknown check ID',
        self::ERR_CHECK_NOT_FOUND => 'Check not found',
        self::ERR_ALREADY_VOIDED_ON => 'Already voided'
    ];

    /** @var string[] */
    protected const WARNING_MESSAGES = [
        self::WARN_PAYMENT_APPLIED => 'Payment applied'
    ];

    // --- Construct ---

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(
            self::ERROR_MESSAGES,
            [],
            self::WARNING_MESSAGES
        );
        return $this;
    }

    // --- Mutate ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addWarning(int $code): static
    {
        $this->getResultStatusCollector()->addWarning($code);
        return $this;
    }

    // --- Query ---

    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function warningCodes(): array
    {
        return $this->getResultStatusCollector()->getWarningCodes();
    }

    public function errorMessage(?string $glue = null): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    public function warningMessage(?string $glue = null): string
    {
        return $this->getResultStatusCollector()->getConcatenatedWarningMessage($glue);
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasAlreadyVoidedError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_ALREADY_VOIDED_ON]);
    }

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }

    public function hasWarning(): bool
    {
        return $this->getResultStatusCollector()->hasWarning();
    }

    public function hasAlreadyAppliedPaymentWarning(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteWarning([self::WARN_PAYMENT_APPLIED]);
    }

    public function logData(): array
    {
        $logData = [];
        if ($this->hasError()) {
            $logData['error'] = $this->errorMessage();
        }
        if ($this->hasWarning()) {
            $logData['warning'] = $this->warningMessage();
        }
        return $logData;
    }

    /**
     * @return ResultStatus[]
     */
    public function getErrorStatuses(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }

    /**
     * @return ResultStatus[]
     */
    public function getWarningStatuses(): array
    {
        return $this->getResultStatusCollector()->getWarningStatuses();
    }
}
