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

namespace Sam\Rtb\Pool\Discovery\Strategy\Fair\Load;

/**
 * Trait RtbdLoadingDatesRangeDecisionDataLoaderCreateTrait
 * @package Sam\Rtb\Pool\Discovery\Strategy\Fair\Load
 */
trait RtbdLoadingDatesRangeDecisionDataLoaderCreateTrait
{
    /**
     * @var RtbdLoadingDatesRangeDecisionDataLoader|null
     */
    protected ?RtbdLoadingDatesRangeDecisionDataLoader $rtbdLoadingDatesRangeDecisionDataLoader = null;

    /**
     * @return RtbdLoadingDatesRangeDecisionDataLoader
     */
    protected function createRtbdLoadingDatesRangeDecisionDataLoader(): RtbdLoadingDatesRangeDecisionDataLoader
    {
        return $this->rtbdLoadingDatesRangeDecisionDataLoader ?: RtbdLoadingDatesRangeDecisionDataLoader::new();
    }

    /**
     * @param RtbdLoadingDatesRangeDecisionDataLoader $rtbdLoadingDatesRangeDecisionDataLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setRtbdLoadingDatesRangeDecisionDataLoader(RtbdLoadingDatesRangeDecisionDataLoader $rtbdLoadingDatesRangeDecisionDataLoader): static
    {
        $this->rtbdLoadingDatesRangeDecisionDataLoader = $rtbdLoadingDatesRangeDecisionDataLoader;
        return $this;
    }
}
