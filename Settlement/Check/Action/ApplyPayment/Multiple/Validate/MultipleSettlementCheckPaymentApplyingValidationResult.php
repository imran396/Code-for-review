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

namespace Sam\Settlement\Check\Action\ApplyPayment\Multiple\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Action\ApplyPayment\Single\Validate\Result\SingleSettlementCheckPaymentApplyingValidationResult;

/**
 * Class MultipleSettlementCheckPaymentApplyingValidationResult
 * @package Sam\Settlement\Check
 */
class MultipleSettlementCheckPaymentApplyingValidationResult extends CustomizableClass
{
    /**
     * @var SingleSettlementCheckPaymentApplyingValidationResult[]
     */
    protected array $failed = [];

    // --- Construct ---

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
        return $this;
    }

    // --- Mutate ---

    public function addError(int $settlementCheckId, SingleSettlementCheckPaymentApplyingValidationResult $singleResult): static
    {
        $this->failed[$settlementCheckId] = $singleResult;
        return $this;
    }

    // --- Query ---

    public function hasError(): bool
    {
        return !empty($this->failed);
    }

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }

    public function logData(): array
    {
        $logData = [];
        foreach ($this->failed as $settlementCheckId => $singleResult) {
            $logData[] = [
                'check id' => $settlementCheckId,
                'error' => $singleResult->errorMessage()
            ];
        }
        return $logData;
    }

    public function getFailed(): array
    {
        return $this->failed;
    }

    /**
     * @return int[]
     */
    public function collectedAlreadyVoidedSettlementCheckIds(): array
    {
        $alreadyVoidedCheckIds = [];
        foreach ($this->failed as $settlementCheckId => $singleValidationResult) {
            if ($singleValidationResult->hasAlreadyVoidedError()) {
                $alreadyVoidedCheckIds[] = $settlementCheckId;
            }
        }
        return $alreadyVoidedCheckIds;
    }

    /**
     * @return int[]
     */
    public function collectedAlreadyAppliedPaymentSettlementCheckIds(): array
    {
        $alreadyAppliedPaymentCheckIds = [];
        foreach ($this->failed as $settlementCheckId => $singleValidationResult) {
            if ($singleValidationResult->hasAlreadyAppliedPaymentError()) {
                $alreadyAppliedPaymentCheckIds[] = $settlementCheckId;
            }
        }
        return $alreadyAppliedPaymentCheckIds;
    }
}
