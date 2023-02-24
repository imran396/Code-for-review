<?php
/**
 * SAM-7685 : Implement house bidder feature execution on scheduled sale closing
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Close\Live;

/**
 * Trait LiveLotCloserCreateTrait
 * @package Sam\Auction\Close\Live
 */
trait LiveLotCloserCreateTrait
{
    protected ?LiveLotCloser $liveLotCloser = null;

    /**
     * @return LiveLotCloser
     */
    protected function createLiveLotCloser(): LiveLotCloser
    {
        return $this->liveLotCloser ?: LiveLotCloser::new();
    }

    /**
     * @param LiveLotCloser $liveLotCloser
     * @return $this
     * @internal
     */
    public function setLiveLotCloser(LiveLotCloser $liveLotCloser): static
    {
        $this->liveLotCloser = $liveLotCloser;
        return $this;
    }
}
