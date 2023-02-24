<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/2/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Feature;

/**
 * Trait RtbdPoolFeatureAvailabilityValidatorAwareTrait
 * @package Sam\Rtb\Pool\Feature
 */
trait RtbdPoolFeatureAvailabilityValidatorAwareTrait
{
    /**
     * @var RtbdPoolFeatureAvailabilityValidator|null
     */
    protected ?RtbdPoolFeatureAvailabilityValidator $rtbdPoolFeatureAvailabilityValidator = null;

    /**
     * @return RtbdPoolFeatureAvailabilityValidator
     */
    protected function getRtbdPoolFeatureAvailabilityValidator(): RtbdPoolFeatureAvailabilityValidator
    {
        if ($this->rtbdPoolFeatureAvailabilityValidator === null) {
            $this->rtbdPoolFeatureAvailabilityValidator = RtbdPoolFeatureAvailabilityValidator::new();
        }
        return $this->rtbdPoolFeatureAvailabilityValidator;
    }

    /**
     * @param RtbdPoolFeatureAvailabilityValidator $rtbdPoolFeatureAvailabilityValidator
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setRtbdPoolFeatureAvailabilityValidator(RtbdPoolFeatureAvailabilityValidator $rtbdPoolFeatureAvailabilityValidator): static
    {
        $this->rtbdPoolFeatureAvailabilityValidator = $rtbdPoolFeatureAvailabilityValidator;
        return $this;
    }
}
