<?php
/**
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Content\Build\Internal\Amount;

use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Content\Build\Internal\Amount\Internal\Load\DataProviderCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class AmountRenderer
 * @package Sam\Settlement\Check
 */
class AmountRenderer extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use DataProviderCreateTrait;
    use NumberFormatterAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Calculate amount that should be assigned to the check of settlement.
     * It depends on balance due and the amounts sum of the existing checks.
     * If amount sum of the existing checks exceed the balance due, then result with zero.
     * @param int $settlementId
     * @param int|null $settlementCheckId
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function calcAmount(int $settlementId, ?int $settlementCheckId, bool $isReadOnlyDb = false): float
    {
        $remainingAmount = $this->createDataProvider()->calcRemainingAmount($settlementId, $settlementCheckId, $isReadOnlyDb);
        if (Floating::lt($remainingAmount, 0.)) {
            $remainingAmount = 0.;
        }
        return $remainingAmount;
    }

    /**
     * Calculates available amount for check by formula: "settlement's total due" - sum of actual check's amounts (exclude voided checks).
     * Result with formatted number according "US Number Formatting" system parameter and without thousand separators.
     * @param int $settlementId
     * @param int $settlementAccountId
     * @param int|null $settlementCheckId
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function renderAmount(
        int $settlementId,
        int $settlementAccountId,
        ?int $settlementCheckId,
        bool $isReadOnlyDb = false
    ): string {
        $amount = $this->calcAmount($settlementId, $settlementCheckId, $isReadOnlyDb);
        $amountFormatted = $this->formatNtoAmount($amount, $settlementAccountId);
        return $amountFormatted;
    }

    /**
     * Format amount value according "US Number Formatting" system parameter and without thousands separators.
     * @param float|null $amount
     * @param int $settlementAccountId
     * @return string
     */
    public function formatNtoAmount(?float $amount, int $settlementAccountId): string
    {
        $numberFormatter = $this->getNumberFormatter()->constructForSettlement($settlementAccountId);
        $amountFormatted = $numberFormatter->formatMoneyNto($amount);
        return $amountFormatted;
    }
}
