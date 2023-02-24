<?php
/**
 * SAM-5346: Rtb asking bid calculator
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           8/13/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\AskingBid;

/**
 * Trait RtbAskingBidDetectorCreateTrait
 * @package Sam\Rtb\AskingBid
 */
trait RtbAskingBidDetectorCreateTrait
{
    /**
     * @var RtbAskingBidDetector|null
     */
    protected ?RtbAskingBidDetector $rtbAskingBidDetector = null;

    /**
     * @return RtbAskingBidDetector
     */
    protected function createRtbAskingBidDetector(): RtbAskingBidDetector
    {
        $rtbAskingBidDetector = $this->rtbAskingBidDetector ?: RtbAskingBidDetector::new();
        return $rtbAskingBidDetector;
    }

    /**
     * @param RtbAskingBidDetector $rtbAskingBidDetector
     * @return static
     * @internal
     */
    public function setRtbAskingBidDetector(RtbAskingBidDetector $rtbAskingBidDetector): static
    {
        $this->rtbAskingBidDetector = $rtbAskingBidDetector;
        return $this;
    }
}
