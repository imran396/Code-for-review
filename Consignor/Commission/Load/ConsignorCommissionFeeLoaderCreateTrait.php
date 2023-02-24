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

namespace Sam\Consignor\Commission\Load;

/**
 * Trait ConsignorCommissionFeeLoaderCreateTrait
 * @package Sam\Consignor\Commission\Load
 */
trait ConsignorCommissionFeeLoaderCreateTrait
{
    /**
     * @var ConsignorCommissionFeeLoader|null
     */
    protected ?ConsignorCommissionFeeLoader $consignorCommissionFeeLoader = null;

    /**
     * @return ConsignorCommissionFeeLoader
     */
    protected function createConsignorCommissionFeeLoader(): ConsignorCommissionFeeLoader
    {
        return $this->consignorCommissionFeeLoader ?: ConsignorCommissionFeeLoader::new();
    }

    /**
     * @param ConsignorCommissionFeeLoader $consignorCommissionFeeLoader
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeLoader(ConsignorCommissionFeeLoader $consignorCommissionFeeLoader): static
    {
        $this->consignorCommissionFeeLoader = $consignorCommissionFeeLoader;
        return $this;
    }
}
