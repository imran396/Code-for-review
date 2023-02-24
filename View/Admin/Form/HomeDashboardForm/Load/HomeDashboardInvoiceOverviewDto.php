<?php
/**
 * SAM-8705: Apply DTO's for loaded data at Home Dashboard page at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\HomeDashboardForm\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class HomeDashboardInvoiceOverviewDto
 * @package Sam\View\Admin\Form\HomeDashboardForm\Load
 */
class HomeDashboardInvoiceOverviewDto extends CustomizableClass
{
    public readonly float $totalBp;
    public readonly float $totalBpCollected;
    public readonly float $totalFees;
    public readonly float $totalFeesCollected;
    public readonly float $totalHighBid;
    public readonly float $totalHighHidAboveReserve;
    public readonly float $totalHp;
    public readonly float $totalHpCollected;
    public readonly float $totalTax;
    public readonly float $totalTaxCollected;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param float $totalBp
     * @param float $totalBpCollected
     * @param float $totalFees
     * @param float $totalFeesCollected
     * @param float $totalHighBid
     * @param float $totalHighHidAboveReserve
     * @param float $totalHp
     * @param float $totalHpCollected
     * @param float $totalTax
     * @param float $totalTaxCollected
     * @return $this
     */
    public function construct(
        float $totalBp,
        float $totalBpCollected,
        float $totalFees,
        float $totalFeesCollected,
        float $totalHighBid,
        float $totalHighHidAboveReserve,
        float $totalHp,
        float $totalHpCollected,
        float $totalTax,
        float $totalTaxCollected
    ): static {
        $this->totalBp = $totalBp;
        $this->totalBpCollected = $totalBpCollected;
        $this->totalFees = $totalFees;
        $this->totalFeesCollected = $totalFeesCollected;
        $this->totalHighBid = $totalHighBid;
        $this->totalHighHidAboveReserve = $totalHighHidAboveReserve;
        $this->totalHp = $totalHp;
        $this->totalHpCollected = $totalHpCollected;
        $this->totalTax = $totalTax;
        $this->totalTaxCollected = $totalTaxCollected;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (float)$row["total_bp"],
            (float)$row["total_bp_collected"],
            (float)$row["total_fees"],
            (float)$row["total_fees_collected"],
            (float)$row["total_high_bid"],
            (float)$row["total_high_bid_above_reserve"],
            (float)$row["total_hp"],
            (float)$row["total_hp_collected"],
            (float)$row["total_tax"],
            (float)$row["total_tax_collected"]
        );
    }
}
