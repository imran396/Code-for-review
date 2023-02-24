<?php
/**
 * SAM-5020: RtbCurrent record change outside of rtb daemon process
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/25/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */


namespace Sam\Rtb\WebClient\Terminate;


/**
 * Trait RtbdConnectionTerminatorAwareTrait
 * @package
 */
trait RtbdConnectionTerminatorAwareTrait
{
    protected ?RtbdConnectionTerminator $rtbdConnectionTerminator = null;

    /**
     * @return RtbdConnectionTerminator
     */
    protected function getRtbdConnectionTerminator(): RtbdConnectionTerminator
    {
        if ($this->rtbdConnectionTerminator === null) {
            $this->rtbdConnectionTerminator = RtbdConnectionTerminator::new();
        }
        return $this->rtbdConnectionTerminator;
    }

    /**
     * @param RtbdConnectionTerminator $rtbdConnectionTerminator
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setRtbdConnectionTerminator(RtbdConnectionTerminator $rtbdConnectionTerminator): static
    {
        $this->rtbdConnectionTerminator = $rtbdConnectionTerminator;
        return $this;
    }
}
