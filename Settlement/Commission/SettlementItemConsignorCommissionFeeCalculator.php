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

use LotItem;
use Sam\Consignor\Commission\Calculate\ApplicableCommission\ApplicableConsignorCommissionFeeDetectorCreateTrait;
use Sam\Consignor\Commission\Calculate\CommissionFeeCalculator\ConsignorCommissionFeeCalculatorCreateTrait;
use Sam\Consignor\Commission\FeeReference\ConsignorFeeReferenceAmountDetectorCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * This class contains high-level methods for calculating commission and fee for a sold lot item
 * and fee for an unsold lot item
 *
 * Class SettlementItemConsignorCommissionFeeCalculator
 * @package Sam\Settlement\Commission
 */
class SettlementItemConsignorCommissionFeeCalculator extends CustomizableClass
{
    use ApplicableConsignorCommissionFeeDetectorCreateTrait;
    use ConsignorCommissionFeeCalculatorCreateTrait;
    use ConsignorFeeReferenceAmountDetectorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Returns a result object that contains calculated commission and fee for the sold item.
     * It uses appropriate commission rule and Hammer price to calc commission and
     * fee rule with detected fee reference amount to calc fee.
     *
     * @param LotItem $lotItem
     * @return SettlementItemConsignorCommissionFee
     */
    public function calcForSoldItem(LotItem $lotItem): SettlementItemConsignorCommissionFee
    {
        $consignorCommissionFeeCalculator = $this->createConsignorCommissionFeeCalculator();
        $applicableConsignorCommissionFeeDetector = $this->createApplicableConsignorCommissionFeeDetector();
        $commissionRuleId = $applicableConsignorCommissionFeeDetector->detectCommissionId(
            $lotItem->Id,
            $lotItem->AuctionId,
            $lotItem->ConsignorId,
            $lotItem->AccountId
        );
        $commissionAmount = 0.;
        if ($commissionRuleId) {
            $commissionAmount = $consignorCommissionFeeCalculator->calculate((float)$lotItem->HammerPrice, $commissionRuleId);
        }

        $feeRuleId = $applicableConsignorCommissionFeeDetector->detectSoldFeeId(
            $lotItem->Id,
            $lotItem->AuctionId,
            $lotItem->ConsignorId,
            $lotItem->AccountId
        );
        $feeAmount = 0.;
        if ($feeRuleId) {
            $feeReferenceAmount = $this->createConsignorFeeReferenceAmountDetector()->detect($lotItem, $lotItem->AuctionId, $feeRuleId);
            $feeAmount = $consignorCommissionFeeCalculator->calculate($feeReferenceAmount, $feeRuleId);
        }

        $result = SettlementItemConsignorCommissionFee::new()->construct($commissionRuleId, $commissionAmount, $feeRuleId, $feeAmount);
        return $result;
    }

    /**
     * Returns a result object that contains calculated fee for the unsold item.
     * It uses appropriate fee rule with detected fee reference amount to calc fee.
     *
     * @param LotItem $lotItem
     * @param int|null $auctionId
     * @return SettlementItemConsignorCommissionFee
     */
    public function calcForUnsoldItem(LotItem $lotItem, ?int $auctionId): SettlementItemConsignorCommissionFee
    {
        $consignorCommissionFeeCalculator = $this->createConsignorCommissionFeeCalculator();
        $applicableConsignorCommissionFeeDetector = $this->createApplicableConsignorCommissionFeeDetector();
        $feeRuleId = $applicableConsignorCommissionFeeDetector->detectUnsoldFeeId(
            $lotItem->Id,
            $auctionId,
            $lotItem->ConsignorId,
            $lotItem->AccountId
        );
        $feeAmount = 0.;
        if ($feeRuleId) {
            $feeReferenceAmount = $this->createConsignorFeeReferenceAmountDetector()->detect($lotItem, $auctionId, $feeRuleId);
            $feeAmount = $consignorCommissionFeeCalculator->calculate($feeReferenceAmount, $feeRuleId);
        }
        $result = SettlementItemConsignorCommissionFee::new()->construct(feeId: $feeRuleId, feeAmount: $feeAmount);
        return $result;
    }
}
