<?php
/**
 * SAM-4038:Refactor Additional Registration Options logic
 * https://bidpath.atlassian.net/browse/SAM-4038
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/11/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\AdditionalRegistrationOption\Save;

/**
 * Trait AdditionalRegistrationOptionProducerAwareTrait
 * @package Sam\Bidder\AuctionBidder\AdditionalRegistrationOption\Save
 */
trait AdditionalRegistrationOptionProducerAwareTrait
{
    protected ?AdditionalRegistrationOptionProducer $additionalRegistrationOptionProducer = null;

    /**
     * @return AdditionalRegistrationOptionProducer
     */
    protected function getAdditionalRegistrationOptionProducer(): AdditionalRegistrationOptionProducer
    {
        if ($this->additionalRegistrationOptionProducer === null) {
            $this->additionalRegistrationOptionProducer = AdditionalRegistrationOptionProducer::new();
        }
        return $this->additionalRegistrationOptionProducer;
    }

    /**
     * @param AdditionalRegistrationOptionProducer $additionalRegistrationOptionProducer
     * @return static
     * @internal
     */
    public function setAdditionalRegistrationOptionProducer(AdditionalRegistrationOptionProducer $additionalRegistrationOptionProducer): static
    {
        $this->additionalRegistrationOptionProducer = $additionalRegistrationOptionProducer;
        return $this;
    }
}
