<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ConsignorCommissionFeeListForm\Load;

/**
 * Trait ConsignorCommissionFeeListLoaderCreateTrait
 * @package Sam\View\Admin\Form\ConsignorCommissionFeeListForm\Load
 */
trait ConsignorCommissionFeeListLoaderCreateTrait
{
    protected ?ConsignorCommissionFeeListLoader $consignorCommissionFeeListLoader = null;

    /**
     * @return ConsignorCommissionFeeListLoader
     */
    protected function createConsignorCommissionFeeListLoader(): ConsignorCommissionFeeListLoader
    {
        return $this->consignorCommissionFeeListLoader ?: ConsignorCommissionFeeListLoader::new();
    }

    /**
     * @param ConsignorCommissionFeeListLoader $consignorCommissionFeeListLoader
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeListLoader(ConsignorCommissionFeeListLoader $consignorCommissionFeeListLoader): static
    {
        $this->consignorCommissionFeeListLoader = $consignorCommissionFeeListLoader;
        return $this;
    }
}
