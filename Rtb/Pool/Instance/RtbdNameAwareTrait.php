<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/15/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Instance;

/**
 * Trait RtbdNameAwareTrait
 * @package
 */
trait RtbdNameAwareTrait
{
    protected string $rtbdName = '';

    /**
     * @return string
     */
    public function getRtbdName(): string
    {
        return $this->rtbdName;
    }

    /**
     * @param string $rtbdName
     * @return static
     */
    public function setRtbdName(string $rtbdName): static
    {
        $this->rtbdName = $rtbdName;
        return $this;
    }
}
