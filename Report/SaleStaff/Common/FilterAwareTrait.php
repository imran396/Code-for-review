<?php
/**
 * SAM-4633:Refactor sales staff report
 * https://bidpath.atlassian.net/browse/SAM-4633
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/11/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\SaleStaff\Common;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Constants\Admin\SaleStaffReportFormConstants;

/**
 * Trait for mutual filtering properties in
 * @package Sam\Report\SaleStaff
 */
trait FilterAwareTrait
{
    use DataRangeTypeAwareTrait;
    use PayoutApplyStatusAwareTrait;
    use PayoutTypeAwareTrait;
    use SalesStaffAwareTrait;

    protected int $consolidationType = SaleStaffReportFormConstants::CT_NONE;

    /**
     * @return int
     */
    public function getConsolidationType(): int
    {
        return $this->consolidationType;
    }

    /**
     * @param int|null $consolidationType
     * @return static
     */
    public function setConsolidationType(?int $consolidationType): static
    {
        $this->consolidationType = Cast::toInt($consolidationType, SaleStaffReportFormConstants::$consolidationTypes)
            ?? SaleStaffReportFormConstants::CT_NONE;
        return $this;
    }
}
