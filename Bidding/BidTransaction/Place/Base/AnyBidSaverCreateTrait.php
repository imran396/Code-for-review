<?php
/**
 * SAM-6143: Simplify live and timed bid registration logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           6/1/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BidTransaction\Place\Base;

/**
 * Trait AnyBidSaverCreateTrait
 * @package Sam\Bidding\BidTransaction\Place\Base
 */
trait AnyBidSaverCreateTrait
{
    protected ?AnyBidSaver $anyBidSaver = null;

    /**
     * @return AnyBidSaver
     */
    protected function createAnyBidSaver(): AnyBidSaver
    {
        return $this->anyBidSaver ?: AnyBidSaver::new();
    }

    /**
     * @param AnyBidSaver $anyBidSaver
     * @return $this
     * @internal
     * @noinspection PhpUnused
     */
    public function setAnyBidSaver(AnyBidSaver $anyBidSaver): static
    {
        $this->anyBidSaver = $anyBidSaver;
        return $this;
    }
}
