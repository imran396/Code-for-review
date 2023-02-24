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

namespace Sam\Settlement\Check\Action\MarkVoided\Multiple\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Action\MarkVoided\Single\Validate\Result\SingleSettlementCheckVoidingValidationResult;

/**
 * Class MultipleSettlementCheckVoidingValidationResult
 * @package Sam\Settlement\Check
 */
class MultipleSettlementCheckVoidingValidationResult extends CustomizableClass
{
    /**
     * @var SingleSettlementCheckVoidingValidationResult[]
     */
    protected array $failed = [];
    /**
     * @var SingleSettlementCheckVoidingValidationResult[]
     */
    private array $warned = [];

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

    public function addError(int $settlementCheckId, SingleSettlementCheckVoidingValidationResult $singleResult): static
    {
        $this->failed[$settlementCheckId] = $singleResult;
        return $this;
    }

    public function addWarning(int $settlementCheckId, SingleSettlementCheckVoidingValidationResult $singleResult): static
    {
        $this->warned[$settlementCheckId] = $singleResult;
        return $this;
    }

    // --- Query ---

    public function hasError(): bool
    {
        return !empty($this->failed);
    }

    public function hasWarning(): bool
    {
        return !empty($this->warned);
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
        foreach ($this->warned as $settlementCheckId => $singleResult) {
            $logData[] = [
                'check id' => $settlementCheckId,
                'warning' => $singleResult->warningMessage()
            ];
        }
        return $logData;
    }

    public function getFailed(): array
    {
        return $this->failed;
    }

    public function getWarned(): array
    {
        return $this->warned;
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
        foreach ($this->warned as $settlementCheckId => $singleValidationResult) {
            if ($singleValidationResult->hasAlreadyAppliedPaymentWarning()) {
                $alreadyAppliedPaymentCheckIds[] = $settlementCheckId;
            }
        }
        return $alreadyAppliedPaymentCheckIds;
    }
}
