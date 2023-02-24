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

/**
 * Trait ConsignorFeeReferenceAmountDetectorCreateTrait
 * @package Sam\Consignor\Commission\FeeReference
 */
trait ConsignorFeeReferenceAmountDetectorCreateTrait
{
    /**
     * @var ConsignorFeeReferenceAmountDetector|null
     */
    protected ?ConsignorFeeReferenceAmountDetector $consignorFeeReferenceAmountDetector = null;

    /**
     * @return ConsignorFeeReferenceAmountDetector
     */
    protected function createConsignorFeeReferenceAmountDetector(): ConsignorFeeReferenceAmountDetector
    {
        return $this->consignorFeeReferenceAmountDetector ?: ConsignorFeeReferenceAmountDetector::new();
    }

    /**
     * @param ConsignorFeeReferenceAmountDetector $consignorFeeReferenceAmountDetector
     * @return static
     * @internal
     */
    public function setConsignorFeeReferenceAmountDetector(ConsignorFeeReferenceAmountDetector $consignorFeeReferenceAmountDetector): static
    {
        $this->consignorFeeReferenceAmountDetector = $consignorFeeReferenceAmountDetector;
        return $this;
    }
}
