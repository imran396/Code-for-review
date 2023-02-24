<?php
/**
 * SAM-6261: Replace Symfony validator and apply ResultStatusCollector for Confirm Shipping Info page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 03, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Register\ConfirmShippingInfo;

/**
 * Trait ConfirmShippingInfoRequiredFieldsDetectorCreateTrait
 * @package Sam\Auction\Register\ConfirmShippingInfo
 */
trait ConfirmShippingInfoRequiredFieldsDetectorCreateTrait
{
    /**
     * @var ConfirmShippingInfoRequiredFieldsDetector|null
     */
    protected ?ConfirmShippingInfoRequiredFieldsDetector $confirmShippingInfoRequiredFieldsDetector = null;

    /**
     * @return ConfirmShippingInfoRequiredFieldsDetector
     */
    protected function createConfirmShippingInfoRequiredFieldsDetector(): ConfirmShippingInfoRequiredFieldsDetector
    {
        return $this->confirmShippingInfoRequiredFieldsDetector ?: ConfirmShippingInfoRequiredFieldsDetector::new();
    }

    /**
     * @param ConfirmShippingInfoRequiredFieldsDetector $detector
     * @return static
     * @internal
     */
    public function setConfirmShippingInfoRequiredFieldsDetector(ConfirmShippingInfoRequiredFieldsDetector $detector): static
    {
        $this->confirmShippingInfoRequiredFieldsDetector = $detector;
        return $this;
    }
}
