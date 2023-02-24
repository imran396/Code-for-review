<?php
/**
 * SAM-6481: Add bidding options on the admin - auction - lots - added lots table
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\PlaceBid\Timed;

/**
 * Trait TimedBidValidatorCreateTrait
 * @package Sam\Application\Controller\Admin\PlaceBid\Timed
 */
trait TimedBidValidatorCreateTrait
{
    protected ?TimedBidValidator $timedBidValidator = null;

    /**
     * @return TimedBidValidator
     */
    protected function createTimedBidValidator(): TimedBidValidator
    {
        return $this->timedBidValidator ?: TimedBidValidator::new();
    }

    /**
     * @param TimedBidValidator $timedBidValidator
     * @return static
     * @internal
     */
    public function setTimedBidValidator(TimedBidValidator $timedBidValidator): static
    {
        $this->timedBidValidator = $timedBidValidator;
        return $this;
    }
}
