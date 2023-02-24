<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Commission;

use Sam\Core\Service\CustomizableClass;

/**
 * Data structure that represents settlement item consignor commission fee data
 *
 * Class SettlementItemConsignorCommissionFee
 * @package Sam\Settlement\Commission
 */
class SettlementItemConsignorCommissionFee extends CustomizableClass
{
    public readonly float $commissionAmount;
    public readonly ?int $commissionId;
    public readonly float $feeAmount;
    public readonly ?int $feeId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(?int $commissionId = null, float $commissionAmount = 0., ?int $feeId = null, float $feeAmount = 0.): static
    {
        $this->commissionId = $commissionId;
        $this->commissionAmount = $commissionAmount;
        $this->feeId = $feeId;
        $this->feeAmount = $feeAmount;
        return $this;
    }
}
