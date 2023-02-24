<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Order\Reorder\Auction\Observe\Internal;

/**
 * Trait ObserverHandlerHelperCreateTrait
 * @package Sam\AuctionLot\Order\Reorder\Auction\Observe\Internal
 * @internal
 */
trait ObserverHandlerHelperCreateTrait
{
    protected ?ObserverHandlerHelper $observerHandlerHelper = null;

    /**
     * @return ObserverHandlerHelper
     */
    protected function createObserverHandlerHelper(): ObserverHandlerHelper
    {
        return $this->observerHandlerHelper ?: ObserverHandlerHelper::new();
    }

    /**
     * @param ObserverHandlerHelper $observerHandlerHelper
     * @return static
     * @internal
     */
    public function setObserverHandlerHelper(ObserverHandlerHelper $observerHandlerHelper): static
    {
        $this->observerHandlerHelper = $observerHandlerHelper;
        return $this;
    }
}
