<?php
/**
 * SAM-4364: Settlement item list loading optimization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Base\SettlementItemList\Internal;

use Sam\Core\Entity\Model\Settlement\Status\SettlementStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Load\Dto\SettlementItemDto;
use Sam\Core\Entity\Model\Settlement\Calculate\SettlementPureCalculatorCreateTrait;

/**
 * Class SettlementItemTaxCalculator
 * @package Sam\View\Base\SettlementItemList\Internal
 * @internal
 */
class SettlementItemTaxCalculator extends CustomizableClass
{
    use SettlementPureCalculatorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param SettlementItemDto $settlementItemDto
     * @return float
     */
    public function calcTaxOnHP(SettlementItemDto $settlementItemDto): float
    {
        $itemTaxOnHP = 0.;
        if ($settlementItemDto->consignorTaxHp) {
            $taxHPPercent = $this->createSettlementPureCalculator()
                ->calcConsignorTaxHpPercent($settlementItemDto->consignorTaxHp, $settlementItemDto->consignorTax);
            if (SettlementStatusPureChecker::new()->isConsignorTaxHpInclusive($settlementItemDto->consignorTaxHpType)) {
                $itemTaxOnHP = $settlementItemDto->hammerPrice - ($settlementItemDto->hammerPrice / (1 + $taxHPPercent));
            } else {
                $itemTaxOnHP = $settlementItemDto->hammerPrice * $taxHPPercent;
            }
        }
        return (float)$itemTaxOnHP;
    }

    /**
     * @param SettlementItemDto $settlementItemDto
     * @return float
     */
    public function calcTaxOnComm(SettlementItemDto $settlementItemDto): float
    {
        $itemTaxOnComm = 0.;
        if ($settlementItemDto->consignorTaxComm) {
            $taxCommPercent = $this->createSettlementPureCalculator()
                ->calcConsignorTaxCommPercent($settlementItemDto->consignorTaxComm, $settlementItemDto->consignorTax);

            // If consignor tax hp is selected and it is inclusive
            // commission tax should be calculated as inclusive as well
            $commissionFee = $settlementItemDto->commission + $settlementItemDto->fee;
            if (
                $settlementItemDto->consignorTaxHp
                && SettlementStatusPureChecker::new()->isConsignorTaxHpInclusive($settlementItemDto->consignorTaxHpType)
            ) {
                $itemTaxOnComm = $commissionFee - ($commissionFee / (1 + $taxCommPercent));
            } else {
                $itemTaxOnComm = $commissionFee * $taxCommPercent;
            }
        }
        return $itemTaxOnComm;
    }
}
