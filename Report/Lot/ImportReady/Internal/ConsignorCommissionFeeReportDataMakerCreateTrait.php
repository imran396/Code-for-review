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

/**
 * Trait ConsignorCommissionFeeReportDataMakerCreateTrait
 * @package Sam\Report\Lot\ImportReady\Internal
 */
trait ConsignorCommissionFeeReportDataMakerCreateTrait
{
    protected ?ConsignorCommissionFeeReportDataMaker $consignorCommissionFeeReportDataMaker = null;

    /**
     * @return ConsignorCommissionFeeReportDataMaker
     */
    protected function createConsignorCommissionFeeReportDataMaker(): ConsignorCommissionFeeReportDataMaker
    {
        return $this->consignorCommissionFeeReportDataMaker ?: ConsignorCommissionFeeReportDataMaker::new();
    }

    /**
     * @param ConsignorCommissionFeeReportDataMaker $consignorCommissionFeeReportDataMaker
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeReportDataMaker(ConsignorCommissionFeeReportDataMaker $consignorCommissionFeeReportDataMaker): static
    {
        $this->consignorCommissionFeeReportDataMaker = $consignorCommissionFeeReportDataMaker;
        return $this;
    }
}
