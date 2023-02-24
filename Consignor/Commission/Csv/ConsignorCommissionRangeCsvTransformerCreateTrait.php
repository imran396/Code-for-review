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

namespace Sam\Consignor\Commission\Csv;

/**
 * Trait ConsignorCommissionRangeCsvTransformerCreateTrait
 * @package Sam\Consignor\Commission\Csv
 */
trait ConsignorCommissionRangeCsvTransformerCreateTrait
{
    /**
     * @var ConsignorCommissionRangeCsvTransformer|null
     */
    protected ?ConsignorCommissionRangeCsvTransformer $consignorCommissionRangeCsvTransformer = null;

    /**
     * @return ConsignorCommissionRangeCsvTransformer
     */
    protected function createConsignorCommissionRangeCsvTransformer(): ConsignorCommissionRangeCsvTransformer
    {
        return $this->consignorCommissionRangeCsvTransformer ?: ConsignorCommissionRangeCsvTransformer::new();
    }

    /**
     * @param ConsignorCommissionRangeCsvTransformer $consignorCommissionRangeCsvTransformer
     * @return static
     * @internal
     */
    public function setConsignorCommissionRangeCsvTransformer(ConsignorCommissionRangeCsvTransformer $consignorCommissionRangeCsvTransformer): static
    {
        $this->consignorCommissionRangeCsvTransformer = $consignorCommissionRangeCsvTransformer;
        return $this;
    }
}
