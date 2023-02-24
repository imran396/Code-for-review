<?php
/**
 * SAM-11182: Extract timed lot bidding logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\OfferBid\Place;

/**
 * Trait OfferBidSaverCreateTrait
 * @package Sam\Bidding\OfferBid\Place
 */
trait OfferBidSaverCreateTrait
{
    protected ?OfferBidSaver $offerBidSaver = null;

    /**
     * @return OfferBidSaver
     */
    protected function createOfferBidSaver(): OfferBidSaver
    {
        return $this->offerBidSaver ?: OfferBidSaver::new();
    }

    /**
     * @param OfferBidSaver $offerBidSaver
     * @return $this
     * @internal
     */
    public function setOfferBidSaver(OfferBidSaver $offerBidSaver): static
    {
        $this->offerBidSaver = $offerBidSaver;
        return $this;
    }
}
