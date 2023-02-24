<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/30/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Instance;

use Sam\Rtb\Pool\Config\RtbdPoolConfigManager;

/**
 * Trait RtbdDescriptorsAwareTrait
 * @package
 */
trait RtbdDescriptorsAwareTrait
{
    /**
     * @var RtbdDescriptor[]
     */
    protected ?array $rtbdDescriptors = null;

    /**
     * @return RtbdDescriptor[]
     */
    public function getRtbdDescriptors(): array
    {
        if ($this->rtbdDescriptors === null) {
            $this->rtbdDescriptors = RtbdPoolConfigManager::new()->getValidDescriptors();
        }
        return $this->rtbdDescriptors;
    }

    /**
     * @param RtbdDescriptor[] $rtbdDescriptors
     * @return static
     */
    public function setRtbdDescriptors(array $rtbdDescriptors): static
    {
        $this->rtbdDescriptors = $rtbdDescriptors;
        return $this;
    }
}
