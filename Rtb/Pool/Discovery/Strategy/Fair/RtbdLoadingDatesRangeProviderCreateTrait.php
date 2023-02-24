<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 06, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Discovery\Strategy\Fair;

/**
 * Trait RtbdLoadingDatesRangeProviderCreateTrait
 * @package Sam\Rtb\Pool\Discovery\Strategy\Fair
 */
trait RtbdLoadingDatesRangeProviderCreateTrait
{
    /**
     * @var RtbdLoadingDatesRangeProvider|null
     */
    protected ?RtbdLoadingDatesRangeProvider $rtbdLoadingDatesRangeProvider = null;

    /**
     * @return RtbdLoadingDatesRangeProvider
     */
    protected function createRtbdLoadingDatesRangeProvider(): RtbdLoadingDatesRangeProvider
    {
        return $this->rtbdLoadingDatesRangeProvider ?: RtbdLoadingDatesRangeProvider::new();
    }

    /**
     * @param RtbdLoadingDatesRangeProvider $rtbdLoadingDatesRangeProvider
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setRtbdLoadingDatesRangeProvider(RtbdLoadingDatesRangeProvider $rtbdLoadingDatesRangeProvider): static
    {
        $this->rtbdLoadingDatesRangeProvider = $rtbdLoadingDatesRangeProvider;
        return $this;
    }
}
