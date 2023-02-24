<?php
/**
 * SAM-9373: Refactor play sound to avoid client side caching of stale files
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sound\RtbMessage\Path;

/**
 * @package Sam\Sound\RtbMessage\Path
 */
trait RtbMessageSoundFilePathResolverCreateTrait
{
    protected ?RtbMessageSoundFilePathResolver $rtbMessageSoundFilePathResolver = null;

    /**
     * @return RtbMessageSoundFilePathResolver
     */
    protected function createRtbMessageSoundFilePathResolver(): RtbMessageSoundFilePathResolver
    {
        return $this->rtbMessageSoundFilePathResolver ?: RtbMessageSoundFilePathResolver::new();
    }

    /**
     * @param RtbMessageSoundFilePathResolver $pathResolver
     * @return $this
     * @internal
     */
    public function setRtbMessageSoundFilePathResolver(RtbMessageSoundFilePathResolver $pathResolver): static
    {
        $this->rtbMessageSoundFilePathResolver = $pathResolver;
        return $this;
    }
}
