<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/28/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Config;

/**
 * Trait RtbPoolConfigManagerAwareTrait
 * @package
 */
trait RtbdPoolConfigManagerAwareTrait
{
    protected ?RtbdPoolConfigManager $rtbdPoolConfigManager = null;

    /**
     * @return RtbdPoolConfigManager
     */
    protected function getRtbdPoolConfigManager(): RtbdPoolConfigManager
    {
        if ($this->rtbdPoolConfigManager === null) {
            $this->rtbdPoolConfigManager = RtbdPoolConfigManager::new();
        }
        return $this->rtbdPoolConfigManager;
    }

    /**
     * @param RtbdPoolConfigManager $rtbdPoolConfigManager
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setRtbdPoolConfigManager(RtbdPoolConfigManager $rtbdPoolConfigManager): static
    {
        $this->rtbdPoolConfigManager = $rtbdPoolConfigManager;
        return $this;
    }
}
