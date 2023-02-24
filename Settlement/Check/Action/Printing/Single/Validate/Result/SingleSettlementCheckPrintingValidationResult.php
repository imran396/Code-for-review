<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\Printing\Single\Validate\Result;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SingleSettlementCheckPrintingValidationResult
 * @package Sam\Settlement\Check
 */
class SingleSettlementCheckPrintingValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_UNKNOWN_ID = 1;
    public const ERR_CHECK_NOT_FOUND = 2;
    public const ERR_EMPTY_CHECK_NO = 3;
    public const ERR_HAS_CHECK_NO_ON_MULTI_PRINT = 4;
    public const ERR_ALREADY_PRINTED_ON = 5;
    public const ERR_ALREADY_VOIDED_ON = 6;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_UNKNOWN_ID => 'Unknown check ID',
        self::ERR_CHECK_NOT_FOUND => 'Check not found',
        self::ERR_EMPTY_CHECK_NO => 'Check# is empty',
        self::ERR_HAS_CHECK_NO_ON_MULTI_PRINT => 'Check# is not empty',
        self::ERR_ALREADY_PRINTED_ON => 'Already printed',
        self::ERR_ALREADY_VOIDED_ON => 'Already voided',
    ];

    /** @var int[] */
    public const UNAVAILABLE_ERRORS = [
        self::ERR_UNKNOWN_ID,
        self::ERR_CHECK_NOT_FOUND
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

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }

    public function hasUnavailableError(): bool
    {
        $has = $this->getResultStatusCollector()->hasConcreteError(self::UNAVAILABLE_ERRORS);
        return $has;
    }

    public function hasCheckNumExistsErrorOnMultiplePrint(): bool
    {
        $has = $this->getResultStatusCollector()->hasConcreteError(self::ERR_HAS_CHECK_NO_ON_MULTI_PRINT);
        return $has;
    }

    public function hasAlreadyPrintedError(): bool
    {
        $has = $this->getResultStatusCollector()->hasConcreteError(self::ERR_ALREADY_PRINTED_ON);
        return $has;
    }

    public function hasAlreadyVoidedError(): bool
    {
        $has = $this->getResultStatusCollector()->hasConcreteError(self::ERR_ALREADY_VOIDED_ON);
        return $has;
    }

    public function logData(): array
    {
        $logData = [];
        if ($this->hasError()) {
            $logData['error'] = $this->errorMessage();
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
}
