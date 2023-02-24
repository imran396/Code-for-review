<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 05, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Discovery\Strategy\Fair\Load;

/**
 * Trait RtbdLoadingLevelLoaderCreateTrait
 * @package Sam\Rtb\Pool\Discovery\Strategy\Fair\Load
 */
trait RtbdLoadingLevelLoaderCreateTrait
{
    /**
     * @var RtbdLoadingLevelLoader|null
     */
    protected ?RtbdLoadingLevelLoader $rtbdLoadingLevelLoader = null;

    /**
     * @return RtbdLoadingLevelLoader
     */
    protected function createRtbdLoadingLevelLoader(): RtbdLoadingLevelLoader
    {
        return $this->rtbdLoadingLevelLoader ?: RtbdLoadingLevelLoader::new();
    }

    /**
     * @param RtbdLoadingLevelLoader $rtbdLoadingLevelLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setRtbdLoadingLevelLoader(RtbdLoadingLevelLoader $rtbdLoadingLevelLoader): static
    {
        $this->rtbdLoadingLevelLoader = $rtbdLoadingLevelLoader;
        return $this;
    }
}
