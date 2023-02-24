<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Load;

/**
 * Trait RtbMessageLoaderCreateTrait
 * @package Sam\Rtb\Load
 */
trait RtbMessageLoaderCreateTrait
{
    /**
     * @var RtbMessageLoader|null
     */
    protected ?RtbMessageLoader $rtbMessageLoader = null;

    /**
     * @return RtbMessageLoader
     */
    protected function createRtbMessageLoader(): RtbMessageLoader
    {
        return $this->rtbMessageLoader ?: RtbMessageLoader::new();
    }

    /**
     * @param RtbMessageLoader $rtbMessageLoader
     * @return static
     * @internal
     */
    public function setRtbMessageLoader(RtbMessageLoader $rtbMessageLoader): static
    {
        $this->rtbMessageLoader = $rtbMessageLoader;
        return $this;
    }
}
