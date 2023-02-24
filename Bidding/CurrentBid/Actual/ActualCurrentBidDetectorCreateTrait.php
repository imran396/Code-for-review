<?php
/**
 * SAM-4025: Actual bid detection issue
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           8/24/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\CurrentBid\Actual;

/**
 * Trait ActualCurrentBidDetectorCreateTrait
 * @package Sam\Bidding\CurrentBid
 */
trait ActualCurrentBidDetectorCreateTrait
{
    protected ?ActualCurrentBidDetector $actualCurrentBidDetector = null;

    /**
     * @return ActualCurrentBidDetector
     */
    protected function createActualCurrentBidDetector(): ActualCurrentBidDetector
    {
        $actualCurrentBidDetector = $this->actualCurrentBidDetector ?: ActualCurrentBidDetector::new();
        return $actualCurrentBidDetector;
    }

    /**
     * @param ActualCurrentBidDetector $actualCurrentBidDetector
     * @return static
     * @internal
     */
    public function setActualCurrentBidDetector(ActualCurrentBidDetector $actualCurrentBidDetector): static
    {
        $this->actualCurrentBidDetector = $actualCurrentBidDetector;
        return $this;
    }
}
