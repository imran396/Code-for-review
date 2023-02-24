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

namespace Sam\Report\User\UserList\Csv\Internal;

use Sam\Consignor\Commission\Csv\ConsignorCommissionRangeCsvTransformerCreateTrait;
use Sam\Consignor\Commission\Load\ConsignorCommissionFeeRangeLoaderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * This class contains methods for providing consignor commission fee header and data for a user list report
 *
 * Class ConsignorCommissionFeeReportDataMaker
 * @package Sam\Report\User\UserList\Csv\Internal
 * @internal
 */
class ConsignorCommissionFeeReportDataMaker extends CustomizableClass
{
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
        return [
            $columnHeaderNames->{Constants\Csv\User::CONSIGNOR_COMMISSION_ID},
            $columnHeaderNames->{Constants\Csv\User::CONSIGNOR_COMMISSION_RANGES},
            $columnHeaderNames->{Constants\Csv\User::CONSIGNOR_COMMISSION_CALCULATION_METHOD},
            $columnHeaderNames->{Constants\Csv\User::CONSIGNOR_SOLD_FEE_ID},
            $columnHeaderNames->{Constants\Csv\User::CONSIGNOR_SOLD_FEE_RANGES},
            $columnHeaderNames->{Constants\Csv\User::CONSIGNOR_SOLD_FEE_CALCULATION_METHOD},
            $columnHeaderNames->{Constants\Csv\User::CONSIGNOR_SOLD_FEE_REFERENCE},
            $columnHeaderNames->{Constants\Csv\User::CONSIGNOR_UNSOLD_FEE_ID},
            $columnHeaderNames->{Constants\Csv\User::CONSIGNOR_UNSOLD_FEE_RANGES},
            $columnHeaderNames->{Constants\Csv\User::CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD},
            $columnHeaderNames->{Constants\Csv\User::CONSIGNOR_UNSOLD_FEE_REFERENCE},
        ];
    }

    /**
     * Make consignor commission fee report data
     *
     * @param array $row
     * @param bool $isConsignor
     * @return array
     */
    public function makeData(array $row, bool $isConsignor): array
    {
        if (!$isConsignor) {
            return array_fill(0, 11, '');
        }

        $data = [];
        $commissionData = $this->makeCommissionFeeData(
            Cast::toInt($row['commission_id']),
            Cast::toInt($row['cons_commission_level']),
            Cast::toInt($row['cons_commission_calculation_method']),
            ''
        );
        array_push($data, $commissionData['id'], $commissionData['ranges'], $commissionData['calculationMethod']);

        $soldFeeData = $this->makeCommissionFeeData(
            Cast::toInt($row['sold_fee_id']),
            Cast::toInt($row['cons_sold_fee_level']),
            Cast::toInt($row['cons_sold_fee_calculation_method']),
            (string)$row['cons_sold_fee_reference']
        );
        array_push($data, $soldFeeData['id'], $soldFeeData['ranges'], $soldFeeData['calculationMethod'], $soldFeeData['feeReference']);

        $unsoldFeeData = $this->makeCommissionFeeData(
            Cast::toInt($row['unsold_fee_id']),
            Cast::toInt($row['cons_unsold_fee_level']),
            Cast::toInt($row['cons_unsold_fee_calculation_method']),
            (string)$row['cons_unsold_fee_reference']
        );
        array_push($data, $unsoldFeeData['id'], $unsoldFeeData['ranges'], $unsoldFeeData['calculationMethod'], $unsoldFeeData['feeReference']);

        return $data;
    }

    /**
     * @param int|null $commissionFeeId
     * @param int|null $level
     * @param int|null $calculationMethod
     * @param string $feeReference
     * @return string[]
     */
    protected function makeCommissionFeeData(?int $commissionFeeId, ?int $level, ?int $calculationMethod, string $feeReference): array
    {
        if ($commissionFeeId && $level !== Constants\ConsignorCommissionFee::LEVEL_ACCOUNT) {
            $ranges = $this->createConsignorCommissionFeeRangeLoader()->loadForConsignorCommissionFee($commissionFeeId, true);
            $data = [
                'id' => '',
                'calculationMethod' => Constants\ConsignorCommissionFee::CALCULATION_METHOD_NAMES[$calculationMethod] ?? '',
                'feeReference' => $feeReference,
                'ranges' => $this->createConsignorCommissionRangeCsvTransformer()->transformEntitiesToCsvString($ranges),
            ];
        } else {
            $data = [
                'id' => $commissionFeeId,
                'calculationMethod' => '',
                'feeReference' => '',
                'ranges' => '',
            ];
        }
        return $data;
    }
}
