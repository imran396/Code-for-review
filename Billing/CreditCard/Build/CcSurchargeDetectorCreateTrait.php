<?php
/**
 * SAM-10335: Allow to adjust CC surcharge per account: Implementation (Dev)
 * https://bidpath.atlassian.net/browse/SAM-10335
 *
 * @author        Oleh Kovalov
 * @since         Apr 24, 2022
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>*
 */

namespace Sam\Billing\CreditCard\Build;

trait CcSurchargeDetectorCreateTrait
{
    /**
     * @var CcSurchargeDetector|null
     */
    protected ?CcSurchargeDetector $ccSurchargeDetector = null;

    /**
     * @return CcSurchargeDetector
     */
    protected function createCcSurchargeDetector(): CcSurchargeDetector
    {
        return $this->ccSurchargeDetector ?: CcSurchargeDetector::new();
    }

    /**
     * @param CcSurchargeDetector $ccSurchargeDetector
     * @return static
     * @internal
     */
    public function setCcSurchargeDetector(CcSurchargeDetector $ccSurchargeDetector): static
    {
        $this->ccSurchargeDetector = $ccSurchargeDetector;
        return $this;
    }
}
