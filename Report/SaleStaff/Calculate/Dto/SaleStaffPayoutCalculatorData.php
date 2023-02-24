<?php
/**
 * DTO for sales staff calculation
 *
 * SAM-4633: Refactor sales staff report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 12, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\SaleStaff\Calculate\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * Class SaleStaffPayoutCalculatorDataDto
 * @package Sam\Report\SaleStaff\Calculate\Dto
 */
class SaleStaffPayoutCalculatorData extends CustomizableClass
{
    public readonly int $accountId;
    public readonly int $salesStaff;
    public readonly int $invoiceStatus;
    public readonly float $hammerPrice;
    public readonly float $buyersPremium;
    public readonly float $total;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $accountId
     * @param float $buyersPremium
     * @param float $hammerPrice
     * @param int $invoiceStatus
     * @param int $salesStaff
     * @param float $total
     * @return $this
     */
    public function construct(
        int $accountId,
        float $buyersPremium,
        float $hammerPrice,
        int $invoiceStatus,
        int $salesStaff,
        float $total
    ): static {
        $this->accountId = $accountId;
        $this->buyersPremium = $buyersPremium;
        $this->hammerPrice = $hammerPrice;
        $this->invoiceStatus = $invoiceStatus;
        $this->salesStaff = $salesStaff;
        $this->total = $total;
        return $this;
    }

    /**
     * Construct DTO with values coming from DB
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (int)$row['account_id'],
            (float)$row['buyers_premium'],
            (float)$row['hammer_price'],
            (int)$row['inv_status'],
            (int)$row['sales_staff'],
            (float)$row['total']
        );
    }

    /**
     * Return array of values with the same keys as we expect for DB fetched data
     * @return array
     */
    public function toArray(): array
    {
        return [
            'account_id' => $this->accountId,
            'buyers_premium' => $this->buyersPremium,
            'hammer_price' => $this->hammerPrice,
            'inv_status' => $this->invoiceStatus,
            'sales_staff' => $this->salesStaff,
            'total' => $this->total,
        ];
    }
}
