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

namespace Sam\Application\Controller\Admin\PlaceBid;

/**
 * Trait PlaceBidValidatorProviderCreateTrait
 * @package Sam\Application\Controller\Admin\PlaceBid
 */
trait PlaceBidValidatorProviderCreateTrait
{
    protected ?PlaceBidValidatorProvider $placeBidValidatorProvider = null;

    /**
     * @return PlaceBidValidatorProvider
     */
    protected function createPlaceBidValidatorProvider(): PlaceBidValidatorProvider
    {
        return $this->placeBidValidatorProvider ?: PlaceBidValidatorProvider::new();
    }

    /**
     * @param PlaceBidValidatorProvider $placeBidValidatorProvider
     * @return static
     * @internal
     */
    public function setPlaceBidValidatorProvider(PlaceBidValidatorProvider $placeBidValidatorProvider): static
    {
        $this->placeBidValidatorProvider = $placeBidValidatorProvider;
        return $this;
    }
}
