<?php
/**
 * SAM-4633: Refactor sales staff report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/12/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\SaleStaff;

use Sam\Core\Constants\Admin\SaleStaffReportFormConstants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class StaffReporterHelper
 * @package Sam\Report\SaleStaff
 */
class StaffReporterHelper extends CustomizableClass
{
    use SortInfoAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Sort rows after aggregating column value
     * @param array $aggregatedRows
     * @return array
     */
    public function sortAggregatedRows(array $aggregatedRows): array
    {
        $sort = $this->getSortColumn();
        $key = match ($sort) {
            SaleStaffReportFormConstants::ORD_SALE_STAFF => 'sales_staff',
            SaleStaffReportFormConstants::ORD_OFFICE_LOCATION => 'office_location',
            SaleStaffReportFormConstants::ORD_HAMMER_PRICE => 'hammer_price',
            SaleStaffReportFormConstants::ORD_BUYERS_PREMIUM => 'buyers_premium',
            SaleStaffReportFormConstants::ORD_SALE_TAX => 'sales_tax',
            SaleStaffReportFormConstants::ORD_TOTAL => 'total',
            default => null,
        };

        if ($key !== null) {
            $temp = array_column($aggregatedRows, $key);
            $direction = $this->isAscendingOrder() ? SORT_ASC : SORT_DESC;
            array_multisort($temp, $direction, $aggregatedRows);
        }
        return $aggregatedRows;
    }

    /**
     * @param array $row
     * @return array
     */
    public function castRowData(array $row): array
    {
        $row['sales_staff'] = Cast::toInt($row['sales_staff']);
        $row['office_location'] = (string)$row['office_location'];
        $row['hammer_price'] = Cast::toFloat($row['hammer_price']);
        $row['buyers_premium'] = Cast::toFloat($row['buyers_premium']);
        $row['sales_tax'] = Cast::toFloat($row['sales_tax']);
        $row['total'] = Cast::toFloat($row['total']);
        return $row;
    }

    /**
     * @return array
     */
    public function createAggregationRow(): array
    {
        $row = [
            'sales_staff' => null,
            'office_location' => null,
            'lot_id' => null,
            'account_id' => null,
            'item_num' => null,
            'lot_name' => null,
            'hammer_price' => 0.,
            'bidder_id' => null,
            'date_sold' => null,
            'auc_name' => null,
            'auc_id' => null,
            'lot_num_prefix' => null,
            'lot_num' => null,
            'lot_num_ext' => null,
            'buyers_premium' => 0.,
            'sales_tax' => 0.,
            'total' => 0.,
            'inv_status' => null,
            'inv_date' => null,
            'payment_date' => null,
            'pay_created' => null,
            'pay_date' => null,
            'inv_status_name' => null,
            'payout' => 0.,
            'buyer_referrer' => '',
            'buyer_referrer_host' => '',
        ];
        return $row;
    }
}
