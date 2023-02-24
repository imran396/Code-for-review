<?php
/**
 * SAM-3924: RTBD scaling by providing a "repeater/ broadcasting" service for viewers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 01, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Discovery\Strategy\RoundRobin\Load;

/**
 * Trait LinkedRtbdLoaderCreateTrait
 * @package Sam\Rtb\Pool\Discovery\Strategy\RoundRobin\Load
 */
trait LinkedRtbdLoaderCreateTrait
{
    /**
     * @var LinkedRtbdLoader|null
     */
    protected ?LinkedRtbdLoader $linkedRtbdLoader = null;

    /**
     * @return LinkedRtbdLoader
     */
    public function createLinkedRtbdLoader(): LinkedRtbdLoader
    {
        return $this->linkedRtbdLoader ?: LinkedRtbdLoader::new();
    }

    /**
     * @param LinkedRtbdLoader $linkedRtbdLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLinkedRtbdLoader(LinkedRtbdLoader $linkedRtbdLoader): static
    {
        $this->linkedRtbdLoader = $linkedRtbdLoader;
        return $this;
    }
}
