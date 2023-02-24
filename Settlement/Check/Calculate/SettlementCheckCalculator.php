<?php
/**
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Calculate;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Calculate\SettlementCalculatorAwareTrait;
use Sam\Storage\ReadRepository\Entity\SettlementCheck\SettlementCheckReadRepositoryCreateTrait;

/**
 * Class SettlementCheckCalculator
 * @package Sam\Settlement\Check
 */
class SettlementCheckCalculator extends CustomizableClass
{
    use SettlementCheckReadRepositoryCreateTrait;
    use SettlementCalculatorAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $settlementId
     * @param array $skipSettlementCheckIds
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function sumExistingCheckAmounts(
        int $settlementId,
        array $skipSettlementCheckIds = [],
        bool $isReadOnlyDb = false
    ): float {
        $row = $this->createSettlementCheckReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterSettlementId($settlementId)
            ->skipId($skipSettlementCheckIds)
            ->select(['SUM(sch.amount) AS total'])
            ->loadRow();
        return (float)($row['total'] ?? 0.);
    }

    /**
     * Calculate difference between settlement's total due and sum of amount in the checks for specific settlement.
     * When the sum of existing check's amount is greater than balance due, then return negative remaining amount.
     * @param int $settlementId
     * @param int|null $settlementCheckId
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function calcRemainingAmount(int $settlementId, ?int $settlementCheckId, bool $isReadOnlyDb = false): float
    {
        $totalDue = $this->getSettlementCalculator()->calcTotalDue($settlementId, $isReadOnlyDb);
        $checkAmountSum = $this->sumExistingCheckAmounts($settlementId, (array)$settlementCheckId, $isReadOnlyDb);
        $checkAmount = $totalDue - $checkAmountSum;
        return $checkAmount;
    }
}
