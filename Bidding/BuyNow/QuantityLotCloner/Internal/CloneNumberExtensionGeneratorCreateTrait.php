<?php
/**
 * SAM-3578: Buyer select from quantity for timed buy now
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BuyNow\QuantityLotCloner\Internal;

/**
 * Trait CloneNumberExtensionGeneratorCreateTrait
 * @package Sam\Bidding\BuyNow\QuantityLotCloner\Internal
 */
trait CloneNumberExtensionGeneratorCreateTrait
{
    protected ?CloneNumberExtensionGenerator $cloneNumberExtensionGenerator = null;

    /**
     * @return CloneNumberExtensionGenerator
     */
    protected function createCloneNumberExtensionGenerator(): CloneNumberExtensionGenerator
    {
        return $this->cloneNumberExtensionGenerator ?: CloneNumberExtensionGenerator::new();
    }

    /**
     * @param CloneNumberExtensionGenerator $cloneNumberExtensionGenerator
     * @return static
     * @internal
     */
    public function setCloneNumberExtensionGenerator(CloneNumberExtensionGenerator $cloneNumberExtensionGenerator): static
    {
        $this->cloneNumberExtensionGenerator = $cloneNumberExtensionGenerator;
        return $this;
    }
}
