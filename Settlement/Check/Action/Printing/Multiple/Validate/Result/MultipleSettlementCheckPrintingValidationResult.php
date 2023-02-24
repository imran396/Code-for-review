<?php
/**
 * SAM-9890: Check Printing for Settlements: Implementation of printing content rendering
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\Printing\Multiple\Validate\Result;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Action\Printing\Single\Validate\Result\SingleSettlementCheckPrintingValidationResult as SingleResult;

/**
 * Class MultipleSettlementCheckPrintingValidationResult
 * @package Sam\Settlement\Check
 */
class MultipleSettlementCheckPrintingValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_INVALID_STARTING_CHECK_NO = 100; // Should not to be same value with any of ERR_* value of Single*PrintingValidationResult

    protected const ERROR_MESSAGES = [
        self::ERR_INVALID_STARTING_CHECK_NO => 'Invalid starting check#',
    ];

    /** @var SingleResult[] */
    protected array $failed = [];

    /**
     * Class instantiation method
     * @return static
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
    public function addError(int $errorCode): static
    {
        $this->getResultStatusCollector()->addError($errorCode);
        return $this;
    }

    public function addErrorBySingleResult(
        SingleResult $singleResult,
        int $settlementCheckId
    ): static {
        $this->failed[$settlementCheckId] = $singleResult;
        return $this;
    }

    // --- Query ---
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function hasError(): bool
    {
        return
            $this->getResultStatusCollector()->hasError()
            || !empty($this->failed);
    }

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }

    public function hasInvalidStartingCheckNoError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_INVALID_STARTING_CHECK_NO);
    }

    public function logData(): array
    {
        $logData = [];
        $errorMessage = $this->getResultStatusCollector()->getConcatenatedErrorMessage();
        if ($errorMessage) {
            $logData[] = $errorMessage;
        }

        foreach ($this->failed ?: [] as $settlementCheckId => $singleResult) {
            $logData[] = [
                'check id' => $settlementCheckId,
                'error' => $singleResult->errorMessage()
            ];
        }

        return $logData;
    }

    /**
     * @return SingleResult[]
     */
    public function getFailed(): array
    {
        return $this->failed;
    }

    /**
     * @return ResultStatus[]
     */
    public function getErrorStatuses(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }

    /**
     * @return int[]
     */
    public function collectCheckIdsOnUnAvailableError(): array
    {
        $collection = $this->collectCheckIdsOnError(SingleResult::UNAVAILABLE_ERRORS);
        return $collection;
    }

    /**
     * @return int[]
     */
    public function collectCheckIdsOnCheckNumExistsError(): array
    {
        $collection = $this->collectCheckIdsOnError([SingleResult::ERR_HAS_CHECK_NO_ON_MULTI_PRINT]);
        return $collection;
    }

    /**
     * @return int[]
     */
    public function collectCheckIdsOnAlreadyPrintedError(): array
    {
        $collection = $this->collectCheckIdsOnError([SingleResult::ERR_ALREADY_PRINTED_ON]);
        return $collection;
    }

    /**
     * @return int[]
     */
    public function collectCheckIdsOnAlreadyVoidedError(): array
    {
        $collection = $this->collectCheckIdsOnError([SingleResult::ERR_ALREADY_VOIDED_ON]);
        return $collection;
    }

    /**
     * @param int[] $errorCollection
     * @return int[]
     */
    protected function collectCheckIdsOnError(array $errorCollection): array
    {
        $collection = [];
        foreach ($this->failed as $settlementCheckId => $singleValidationResult) {
            if (
                $errorCollection === SingleResult::UNAVAILABLE_ERRORS
                && $singleValidationResult->hasUnavailableError()
            ) {
                $collection[] = $settlementCheckId;
            } elseif (
                $errorCollection === [SingleResult::ERR_HAS_CHECK_NO_ON_MULTI_PRINT]
                && $singleValidationResult->hasCheckNumExistsErrorOnMultiplePrint()
            ) {
                $collection[] = $settlementCheckId;
            } elseif (
                $errorCollection === [SingleResult::ERR_ALREADY_PRINTED_ON]
                && $singleValidationResult->hasAlreadyPrintedError()
            ) {
                $collection[] = $settlementCheckId;
            } elseif (
                $errorCollection === [SingleResult::ERR_ALREADY_VOIDED_ON]
                && $singleValidationResult->hasAlreadyVoidedError()
            ) {
                $collection[] = $settlementCheckId;
            }
        }
        return $collection;
    }
}
