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
 * Trait PlaceBidHandlerProviderCreateTrait
 * @package Sam\Application\Controller\Admin\PlaceBid
 */
trait PlaceBidHandlerProviderCreateTrait
{
    protected ?PlaceBidHandlerProvider $placeBidHandlerProvider = null;

    /**
     * @return PlaceBidHandlerProvider
     */
    protected function createPlaceBidHandlerProvider(): PlaceBidHandlerProvider
    {
        return $this->placeBidHandlerProvider ?: PlaceBidHandlerProvider::new();
    }

    /**
     * @param PlaceBidHandlerProvider $placeBidHandlerProvider
     * @return static
     * @internal
     */
    public function setPlaceBidHandlerProvider(PlaceBidHandlerProvider $placeBidHandlerProvider): static
    {
        $this->placeBidHandlerProvider = $placeBidHandlerProvider;
        return $this;
    }
}
