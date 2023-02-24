<?php
/**
 * SAM-5346: Rtb asking bid calculator
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

namespace Sam\Rtb\AskingBid;

/**
 * Trait RtbAskingBidUpdaterCreateTrait
 * @package Sam\Rtb\AskingBid
 */
trait RtbAskingBidUpdaterCreateTrait
{
    /**
     * @var RtbAskingBidUpdater|null
     */
    protected ?RtbAskingBidUpdater $rtbAskingBidUpdater = null;

    /**
     * @return RtbAskingBidUpdater
     */
    protected function createRtbAskingBidUpdater(): RtbAskingBidUpdater
    {
        $rtbAskingBidUpdater = $this->rtbAskingBidUpdater ?: RtbAskingBidUpdater::new();
        return $rtbAskingBidUpdater;
    }

    /**
     * @param RtbAskingBidUpdater $rtbAskingBidUpdater
     * @return static
     */
    public function setRtbAskingBidUpdater(RtbAskingBidUpdater $rtbAskingBidUpdater): static
    {
        $this->rtbAskingBidUpdater = $rtbAskingBidUpdater;
        return $this;
    }
}
