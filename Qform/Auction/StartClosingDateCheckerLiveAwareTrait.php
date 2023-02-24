<?php
/**
 * SAM-6645: Start closing date of hybrid auction in the past prevents from saving auction info
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct. 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\Auction;


/**
 * Trait StartClosingDateCheckerLiveAwareTrait
 * @package Sam\Qform\Auction
 */
trait StartClosingDateCheckerLiveAwareTrait
{
    protected ?StartClosingDateCheckerLive $startClosingDateCheckerLive = null;

    /**
     * @return StartClosingDateCheckerLive
     */
    protected function getStartClosingDateCheckerLive(): StartClosingDateCheckerLive
    {
        if ($this->startClosingDateCheckerLive === null) {
            $this->startClosingDateCheckerLive = StartClosingDateCheckerLive::new();
        }
        return $this->startClosingDateCheckerLive;
    }

    /**
     * @param StartClosingDateCheckerLive $startClosingDateCheckerLive
     * @return static
     * @internal
     */
    public function setStartClosingDateCheckerLive(StartClosingDateCheckerLive $startClosingDateCheckerLive): static
    {
        $this->startClosingDateCheckerLive = $startClosingDateCheckerLive;
        return $this;
    }
}
