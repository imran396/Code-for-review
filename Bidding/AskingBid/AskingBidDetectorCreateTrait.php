<?php
/**
 * SAM-4974: Move asking bid calculation
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           8/14/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\AskingBid;

/**
 * Trait AskingBidDetectorCreateTrait
 * @package Sam\Bidding\AskingBid
 */
trait AskingBidDetectorCreateTrait
{
    protected ?AskingBidDetector $askingBidDetector = null;

    /**
     * @return AskingBidDetector
     */
    protected function createAskingBidDetector(): AskingBidDetector
    {
        $askingBidDetector = $this->askingBidDetector ?: AskingBidDetector::new();
        return $askingBidDetector;
    }

    /**
     * @param AskingBidDetector $askingBidDetector
     * @return static
     * @internal
     */
    public function setAskingBidDetector(AskingBidDetector $askingBidDetector): static
    {
        $this->askingBidDetector = $askingBidDetector;
        return $this;
    }
}
