<?php
/**
 * SAM-6143: Simplify live and timed bid registration logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           5/27/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BidTransaction\Place\Live;

/**
 * Trait LiveBidSaverCreateTrait
 * @package
 */
trait LiveBidSaverCreateTrait
{
    /**
     * @var LiveBidSaver|null
     */
    protected ?LiveBidSaver $liveBidSaver = null;

    /**
     * @return LiveBidSaver
     */
    protected function createLiveBidSaver(): LiveBidSaver
    {
        return $this->liveBidSaver ?: LiveBidSaver::new();
    }

    /**
     * @param LiveBidSaver $liveBidSaver
     * @return $this
     * @noinspection PhpUnused
     * @internal
     */
    public function setLiveBidSaver(LiveBidSaver $liveBidSaver): static
    {
        $this->liveBidSaver = $liveBidSaver;
        return $this;
    }
}
