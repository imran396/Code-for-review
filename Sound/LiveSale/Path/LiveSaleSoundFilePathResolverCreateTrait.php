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

namespace Sam\Sound\LiveSale\Path;

/**
 * @package Sam\Sound\LiveSale\Path
 */
trait LiveSaleSoundFilePathResolverCreateTrait
{
    protected ?LiveSaleSoundFilePathResolver $liveSaleSoundFilePathResolver = null;

    /**
     * @return LiveSaleSoundFilePathResolver
     */
    protected function createLiveSaleSoundFilePathResolver(): LiveSaleSoundFilePathResolver
    {
        return $this->liveSaleSoundFilePathResolver ?: LiveSaleSoundFilePathResolver::new();
    }

    /**
     * @param LiveSaleSoundFilePathResolver $pathResolver
     * @return $this
     * @internal
     */
    public function setLiveSaleSoundFilePathResolver(LiveSaleSoundFilePathResolver $pathResolver): static
    {
        $this->liveSaleSoundFilePathResolver = $pathResolver;
        return $this;
    }
}
