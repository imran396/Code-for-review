<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May. 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Calculate\ApplicableCommission;

/**
 * Trait ApplicableConsignorCommissionFeeDetectorCreateTrait
 * @package Sam\Consignor\Commission\Calculate
 */
trait ApplicableConsignorCommissionFeeDetectorCreateTrait
{
    protected ?ApplicableConsignorCommissionFeeDetector $applicableConsignorCommissionFeeDetector = null;

    /**
     * @return ApplicableConsignorCommissionFeeDetector
     */
    protected function createApplicableConsignorCommissionFeeDetector(): ApplicableConsignorCommissionFeeDetector
    {
        return $this->applicableConsignorCommissionFeeDetector ?: ApplicableConsignorCommissionFeeDetector::new();
    }

    /**
     * @param ApplicableConsignorCommissionFeeDetector $applicableConsignorCommissionFeeDetector
     * @return static
     * @internal
     */
    public function setApplicableConsignorCommissionFeeDetector(ApplicableConsignorCommissionFeeDetector $applicableConsignorCommissionFeeDetector): static
    {
        $this->applicableConsignorCommissionFeeDetector = $applicableConsignorCommissionFeeDetector;
        return $this;
    }
}
