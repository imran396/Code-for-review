<?php
/**
 * SAM-9338: Apply DTO for coupon list page at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\CouponListForm\Load;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class CouponListDto
 * @package Sam\View\Admin\Form\CouponListForm\Load
 */
class CouponListDto extends CustomizableClass
{
    public readonly string $code;
    public readonly int $couponStatusId;
    public readonly int $couponType;
    public readonly string $endDate;
    public readonly float $fixedAmountOff;
    public readonly int $id;
    public readonly string $title;
    public readonly float $minPurchaseAmt;
    public readonly float $percentageOff;
    public readonly ?int $perUser;
    public readonly string $startDate;
    public readonly int $timezoneId;
    public readonly int $waiveAdditionalCharges;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $code
     * @param int $couponStatusId
     * @param int $couponType
     * @param string $endDate
     * @param float $fixedAmountOff
     * @param int $id
     * @param string $title
     * @param float $minPurchaseAmt
     * @param float $percentageOff
     * @param int|null $perUser
     * @param string $startDate
     * @param int $timezoneId
     * @param int $waiveAdditionalCharges
     * @return $this
     */
    public function construct(
        string $code,
        int $couponStatusId,
        int $couponType,
        string $endDate,
        float $fixedAmountOff,
        int $id,
        string $title,
        float $minPurchaseAmt,
        float $percentageOff,
        ?int $perUser,
        string $startDate,
        int $timezoneId,
        int $waiveAdditionalCharges
    ): static {
        $this->code = $code;
        $this->couponStatusId = $couponStatusId;
        $this->couponType = $couponType;
        $this->endDate = $endDate;
        $this->fixedAmountOff = $fixedAmountOff;
        $this->id = $id;
        $this->title = $title;
        $this->minPurchaseAmt = $minPurchaseAmt;
        $this->percentageOff = $percentageOff;
        $this->perUser = $perUser;
        $this->startDate = $startDate;
        $this->timezoneId = $timezoneId;
        $this->waiveAdditionalCharges = $waiveAdditionalCharges;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (string)$row['code'],
            (int)$row['coupon_status_id'],
            (int)$row['coupon_type'],
            (string)$row['end_date'],
            (float)$row['fixed_amount_off'],
            (int)$row['id'],
            (string)$row['title'],
            (float)$row['min_purchase_amt'],
            (float)$row['percentage_off'],
            Cast::toInt($row['per_user'], Constants\Type::F_INT_POSITIVE),
            (string)$row['start_date'],
            (int)$row['timezone_id'],
            (int)$row['waive_additional_charges']
        );
    }
}
