<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/21/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\State\Reset;

/**
 * Trait RtbStateResetterAwareTrait
 * @package Sam\Rtb\State\Reset
 */
trait RtbStateResetterAwareTrait
{
    protected ?RtbStateResetter $rtbStateResetter = null;

    /**
     * @return RtbStateResetter
     */
    protected function getRtbStateResetter(): RtbStateResetter
    {
        if ($this->rtbStateResetter === null) {
            $this->rtbStateResetter = RtbStateResetter::new();
        }
        return $this->rtbStateResetter;
    }

    /**
     * @param RtbStateResetter $rtbStateResetter
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setRtbStateResetter(RtbStateResetter $rtbStateResetter): static
    {
        $this->rtbStateResetter = $rtbStateResetter;
        return $this;
    }
}
