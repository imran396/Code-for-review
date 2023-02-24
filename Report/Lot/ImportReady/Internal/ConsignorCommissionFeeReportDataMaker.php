<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\ImportReady\Internal;

use AuctionLotItem;
use LotItem;
use Sam\Consignor\Commission\Csv\ConsignorCommissionRangeCsvTransformerCreateTrait;
use Sam\Consignor\Commission\Load\ConsignorCommissionFeeLoaderCreateTrait;
use Sam\Consignor\Commission\Load\ConsignorCommissionFeeRangeLoaderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * This class contains methods for providing consignor commission fee header and data for an import-ready report
 *
 * Class ConsignorCommissionFeeReportDataMaker
 * @package Sam\Report\Lot\ImportReady\Internal
 * @internal
 */
class ConsignorCommissionFeeReportDataMaker extends CustomizableClass
{
    use ConsignorCommissionFeeLoaderCreateTrait;
    use ConsignorCommissionFeeRangeLoaderCreateTrait;
    use ConsignorCommissionRangeCsvTransformerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Returns consignor commission fee report column names
     *
     * @param object $columnHeaderNames
     * @return array
     */
    public function makeHeaders(object $columnHeaderNames): array
    {
        $headers = [];
        foreach (self::headerKey() as $columnName) {
            $headers[] = $columnHeaderNames->{$columnName};
        }
        return $headers;
    }

    /**
     * Make consignor commission fee report data
     *
     * @param LotItem $lotItem
     * @param AuctionLotItem $auctionLot
     * @return array
     */
    public function makeData(LotItem $lotItem, AuctionLotItem $auctionLot): array
    {
        $data = [];
        $commissionId = $auctionLot->ConsignorCommissionId ?? $lotItem->ConsignorCommissionId;
        $commissionData = $this->makeCommissionFeeData($commissionId);
        $data[Constants\Csv\Lot::CONSIGNOR_COMMISSION_ID] = $commissionData['id'];
        $data[Constants\Csv\Lot::CONSIGNOR_COMMISSION_RANGES] = $commissionData['ranges'];
        $data[Constants\Csv\Lot::CONSIGNOR_COMMISSION_CALCULATION_METHOD] = $commissionData['calculationMethod'];

        $soldFeeId = $auctionLot->ConsignorSoldFeeId ?? $lotItem->ConsignorSoldFeeId;
        $soldFeeData = $this->makeCommissionFeeData($soldFeeId);
        $data[Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_ID] = $soldFeeData['id'];
        $data[Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_RANGES] = $soldFeeData['ranges'];
        $data[Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD] = $soldFeeData['calculationMethod'];
        $data[Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_REFERENCE] = $soldFeeData['feeReference'];

        $unsoldFeeId = $auctionLot->ConsignorUnsoldFeeId ?? $lotItem->ConsignorUnsoldFeeId;
        $unsoldFeeData = $this->makeCommissionFeeData($unsoldFeeId);
        $data[Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_ID] = $unsoldFeeData['id'];
        $data[Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_RANGES] = $unsoldFeeData['ranges'];
        $data[Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD] = $unsoldFeeData['calculationMethod'];
        $data[Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_REFERENCE] = $unsoldFeeData['feeReference'];
        return $data;
    }

    /**
     * @param int|null $commissionFeeId
     * @return array|string[]
     */
    protected function makeCommissionFeeData(?int $commissionFeeId): array
    {
        $commissionFee = $this->createConsignorCommissionFeeLoader()->load($commissionFeeId, true);
        if ($commissionFee && !$commissionFee->isAccountLevel()) {
            $ranges = $this->createConsignorCommissionFeeRangeLoader()->loadForConsignorCommissionFee($commissionFeeId, true);
            $data = [
                'id' => '',
                'calculationMethod' => Constants\ConsignorCommissionFee::CALCULATION_METHOD_NAMES[$commissionFee->CalculationMethod],
                'feeReference' => $commissionFee->FeeReference,
                'ranges' => $this->createConsignorCommissionRangeCsvTransformer()->transformEntitiesToCsvString($ranges),
            ];
        } else {
            $data = [
                'id' => (string)$commissionFeeId,
                'calculationMethod' => '',
                'feeReference' => '',
                'ranges' => '',
            ];
        }
        return $data;
    }

    public static function headerKey(): array
    {
        return [
            Constants\Csv\Lot::CONSIGNOR_COMMISSION_ID,
            Constants\Csv\Lot::CONSIGNOR_COMMISSION_RANGES,
            Constants\Csv\Lot::CONSIGNOR_COMMISSION_CALCULATION_METHOD,
            Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_ID,
            Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_RANGES,
            Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD,
            Constants\Csv\Lot::CONSIGNOR_SOLD_FEE_REFERENCE,
            Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_ID,
            Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_RANGES,
            Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD,
            Constants\Csv\Lot::CONSIGNOR_UNSOLD_FEE_REFERENCE,
        ];
    }
}
