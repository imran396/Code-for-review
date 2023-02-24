<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May. 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\FeeReference;

use LotItem;
use LotItemCustField;
use Sam\Consignor\Commission\FeeReference\Internal\Load\ConsignorFeeReferenceBidLoaderCreateTrait;
use Sam\Consignor\Commission\Load\ConsignorCommissionFeeLoaderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;

/**
 * Class ConsignorFeeReferenceAmountDetector
 * @package Sam\Consignor\Commission\Calculate
 */
class ConsignorFeeReferenceAmountDetector extends CustomizableClass
{
    use ConsignorCommissionFeeLoaderCreateTrait;
    use ConsignorFeeReferenceBidLoaderCreateTrait;
    use LotCustomDataLoaderCreateTrait;
    use LotCustomFieldLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Returns lot item fee reference amount based on commission fee rule
     *
     * @param LotItem $lotItem
     * @param int|null $auctionId
     * @param int $commissionFeeId
     * @return float
     */
    public function detect(LotItem $lotItem, ?int $auctionId, int $commissionFeeId): float
    {
        $commissionFee = $this->createConsignorCommissionFeeLoader()->load($commissionFeeId);
        if (!$commissionFee) {
            log_error("Available ConsignorCommissionFee not found" . composeSuffix(['id' => $commissionFeeId]));
            return 0.;
        }
        switch ($commissionFee->FeeReference) {
            case Constants\ConsignorCommissionFee::FEE_REFERENCE_ZERO:
                $amount = 0.;
                break;
            case Constants\ConsignorCommissionFee::FEE_REFERENCE_HAMMER_PRICE:
                $amount = $lotItem->HammerPrice;
                break;
            case Constants\ConsignorCommissionFee::FEE_REFERENCE_START_BID:
                $amount = $lotItem->StartingBid;
                break;
            case Constants\ConsignorCommissionFee::FEE_REFERENCE_RESERVE_PRICE:
                $amount = $lotItem->ReservePrice;
                break;
            case Constants\ConsignorCommissionFee::FEE_REFERENCE_MAX_BID:
                $amount = $this->createConsignorFeeReferenceBidLoader()->loadMaxBid($lotItem->Id, $auctionId);
                break;
            case Constants\ConsignorCommissionFee::FEE_REFERENCE_CURRENT_BID:
                $amount = $this->createConsignorFeeReferenceBidLoader()->loadCurrentBid($lotItem->Id, $auctionId);
                break;
            case Constants\ConsignorCommissionFee::FEE_REFERENCE_LOW_ESTIMATE:
                $amount = $lotItem->LowEstimate;
                break;
            case Constants\ConsignorCommissionFee::FEE_REFERENCE_HIGH_ESTIMATE:
                $amount = $lotItem->HighEstimate;
                break;
            case Constants\ConsignorCommissionFee::FEE_REFERENCE_COST:
                $amount = $lotItem->Cost;
                break;
            case Constants\ConsignorCommissionFee::FEE_REFERENCE_REPLACEMENT_PRICE:
                $amount = $lotItem->ReplacementPrice;
                break;
            default:
                $amount = 0.;
                if (str_starts_with($commissionFee->FeeReference, Constants\ConsignorCommissionFee::FEE_REFERENCE_CUSTOM_FIELD_PREFIX)) {
                    $amount = $this->detectCustomFieldReferenceAmount($lotItem->Id, $commissionFee->FeeReference);
                }
        }
        return $amount ?? 0.;
    }

    /**
     * @param int $lotItemId
     * @param string $consignorFeeReference
     * @return float
     */
    protected function detectCustomFieldReferenceAmount(int $lotItemId, string $consignorFeeReference): float
    {
        $amount = 0.;
        $customFieldId = (int)str_replace(Constants\ConsignorCommissionFee::FEE_REFERENCE_CUSTOM_FIELD_PREFIX, '', $consignorFeeReference);
        $customField = $this->loadLotCustomField($customFieldId);
        if ($customField) {
            $customFieldData = $this->createLotCustomDataLoader()->load($customFieldId, $lotItemId);
            if ($customFieldData) {
                switch ($customField->Type) {
                    case Constants\CustomField::TYPE_INTEGER:
                        $amount = (float)$customFieldData->Numeric;
                        break;
                    case Constants\CustomField::TYPE_DECIMAL:
                        $precision = (int)$customField->Parameters;
                        $amount = $customFieldData->calcDecimalValue($precision) ?? 0.;
                        break;
                }
            }
        }
        return $amount;
    }

    /**
     * @param int $id
     * @return LotItemCustField|null
     */
    protected function loadLotCustomField(int $id): ?LotItemCustField
    {
        //Use loadAll to enable cache and decrease queries to DB
        $fields = $this->createLotCustomFieldLoader()->loadAll();
        foreach ($fields as $field) {
            if ($field->Id === $id) {
                return $field;
            }
        }
        return null;
    }
}
