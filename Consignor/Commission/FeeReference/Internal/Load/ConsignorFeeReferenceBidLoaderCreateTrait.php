<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May. 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\FeeReference\Internal\Load;

/**
 * Trait ConsignorFeeReferenceBidLoaderCreateTrait
 * @package Sam\Consignor\Commission\FeeReference\Internal\Load
 * @internal
 */
trait ConsignorFeeReferenceBidLoaderCreateTrait
{
    /**
     * @var ConsignorFeeReferenceBidLoader|null
     */
    protected ?ConsignorFeeReferenceBidLoader $consignorFeeReferenceBidLoader = null;

    /**
     * @return ConsignorFeeReferenceBidLoader
     */
    protected function createConsignorFeeReferenceBidLoader(): ConsignorFeeReferenceBidLoader
    {
        return $this->consignorFeeReferenceBidLoader ?: ConsignorFeeReferenceBidLoader::new();
    }

    /**
     * @param ConsignorFeeReferenceBidLoader $consignorFeeReferenceBidLoader
     * @return static
     * @internal
     */
    public function setConsignorFeeReferenceBidLoader(ConsignorFeeReferenceBidLoader $consignorFeeReferenceBidLoader): static
    {
        $this->consignorFeeReferenceBidLoader = $consignorFeeReferenceBidLoader;
        return $this;
    }
}
