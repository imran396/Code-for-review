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

namespace Sam\Application\Controller\Admin\PlaceBid\LiveOrHybrid;

/**
 * Trait LiveOrHybridAbsenteeBidValidatorCreateTrait
 * @package Sam\Application\Controller\Admin\PlaceBid\Internal\LiveOrHybrid
 */
trait LiveOrHybridAbsenteeBidValidatorCreateTrait
{
    protected ?LiveOrHybridAbsenteeBidValidator $liveOrHybridAbsenteeBidValidator = null;

    /**
     * @return LiveOrHybridAbsenteeBidValidator
     */
    protected function createLiveOrHybridAbsenteeBidValidator(): LiveOrHybridAbsenteeBidValidator
    {
        return $this->liveOrHybridAbsenteeBidValidator ?: LiveOrHybridAbsenteeBidValidator::new();
    }

    /**
     * @param LiveOrHybridAbsenteeBidValidator $liveOrHybridAbsenteeBidValidator
     * @return static
     * @internal
     */
    public function setLiveOrHybridAbsenteeBidValidator(LiveOrHybridAbsenteeBidValidator $liveOrHybridAbsenteeBidValidator): static
    {
        $this->liveOrHybridAbsenteeBidValidator = $liveOrHybridAbsenteeBidValidator;
        return $this;
    }
}
