<?php
/**
 * SAM-4364: Settlement item list loading optimization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\Settlement\Calculate;

use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementPureCalculator
 * @package Sam\Core\Entity\Model\Settlement\Calculate
 */
class SettlementPureCalculator extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param float $consignorTaxHp
     * @param float $consignorTax
     * @return float
     */
    public function calcConsignorTaxHpPercent(float $consignorTaxHp, float $consignorTax): float
    {
        if (!$consignorTaxHp) {
            return 0.;
        }
        return $consignorTax / 100.;
    }

    /**
     * @param float $consignorTaxComm
     * @param float $consignorTax
     * @return float
     */
    public function calcConsignorTaxCommPercent(float $consignorTaxComm, float $consignorTax): float
    {
        if (!$consignorTaxComm) {
            return 0.;
        }
        return $consignorTax / 100.;
    }
}
