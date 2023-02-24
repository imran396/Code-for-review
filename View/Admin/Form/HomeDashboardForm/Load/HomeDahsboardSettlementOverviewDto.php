<?php
/**
 * SAM-8705: Apply DTO's for loaded data at Home Dashboard page at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\HomeDashboardForm\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class HomeDahsboardSettlementOverviewDto
 * @package Sam\View\Admin\Form\HomeDashboardForm\Load
 */
class HomeDahsboardSettlementOverviewDto extends CustomizableClass
{
    public readonly float $totalCommission;
    public readonly float $totalCommissionSettled;
    public readonly float $totalFee;
    public readonly float $totalFeeSettled;
    public readonly float $totalHp;
    public readonly float $totalHpSettled;
    public readonly float $totalTax;
    public readonly float $totalTaxSettled;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param float $totalCommission
     * @param float $totalCommissionSettled
     * @param float $totalFee
     * @param float $totalFeeSettled
     * @param float $totalHp
     * @param float $totalHpSettled
     * @param float $totalTax
     * @param float $totalTaxSettled
     * @return static
     */
    public function construct(
        float $totalCommission,
        float $totalCommissionSettled,
        float $totalFee,
        float $totalFeeSettled,
        float $totalHp,
        float $totalHpSettled,
        float $totalTax,
        float $totalTaxSettled
    ): static {
        $this->totalCommission = $totalCommission;
        $this->totalCommissionSettled = $totalCommissionSettled;
        $this->totalFee = $totalFee;
        $this->totalFeeSettled = $totalFeeSettled;
        $this->totalHp = $totalHp;
        $this->totalHpSettled = $totalHpSettled;
        $this->totalTax = $totalTax;
        $this->totalTaxSettled = $totalTaxSettled;
        return $this;
    }

    /**
     * @param array $row
     * @return static
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (float)$row["total_commission"],
            (float)$row["total_commission_settled"],
            (float)$row["total_fee"],
            (float)$row["total_fee_settled"],
            (float)$row["total_hp"],
            (float)$row["total_hp_settled"],
            (float)$row["total_tax"],
            (float)$row["total_tax_settled"]
        );
    }
}
