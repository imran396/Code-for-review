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

namespace Sam\Settlement\Check\Action\MarkCleared\Multiple\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Action\MarkCleared\Single\Validate\SingleSettlementCheckClearedMarkingValidationResult;

/**
 * Class MultipleSettlementCheckClearedMarkingValidationResult
 * @package Sam\Settlement\Check
 */
class MultipleSettlementCheckClearedMarkingValidationResult extends CustomizableClass
{
    /**
     * @var SingleSettlementCheckClearedMarkingValidationResult[]
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

    public function addError(int $settlementCheckId, SingleSettlementCheckClearedMarkingValidationResult $singleResult): static
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
    public function collectedEmptyCheckNoSettlementCheckIds(): array
    {
        $emptyCheckNoCheckIds = [];
        foreach ($this->failed as $settlementCheckId => $singleValidationResult) {
            if ($singleValidationResult->hasEmptyCheckNoError()) {
                $emptyCheckNoCheckIds[] = $settlementCheckId;
            }
        }
        return $emptyCheckNoCheckIds;
    }

    /**
     * @return int[]
     */
    public function collectedNotPrintedSettlementCheckIds(): array
    {
        $notPrintedCheckIds = [];
        foreach ($this->failed as $settlementCheckId => $singleValidationResult) {
            if ($singleValidationResult->hasNotPrintedError()) {
                $notPrintedCheckIds[] = $settlementCheckId;
            }
        }
        return $notPrintedCheckIds;
    }

    /**
     * @return int[]
     */
    public function collectedNotPostedSettlementCheckIds(): array
    {
        $notPostedCheckIds = [];
        foreach ($this->failed as $settlementCheckId => $singleValidationResult) {
            if ($singleValidationResult->hasNotPostedError()) {
                $notPostedCheckIds[] = $settlementCheckId;
            }
        }
        return $notPostedCheckIds;
    }

    /**
     * @return int[]
     */
    public function collectedAlreadyClearedSettlementCheckIds(): array
    {
        $alreadyClearedCheckIds = [];
        foreach ($this->failed as $settlementCheckId => $singleValidationResult) {
            if ($singleValidationResult->hasAlreadyClearedError()) {
                $alreadyClearedCheckIds[] = $settlementCheckId;
            }
        }
        return $alreadyClearedCheckIds;
    }

}
