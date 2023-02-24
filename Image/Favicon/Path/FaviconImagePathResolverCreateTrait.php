<?php
/**
 * SAM-11607: Custom favicon
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 14, 2023
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Image\Favicon\Path;

trait FaviconImagePathResolverCreateTrait
{
    /**
     * @var FaviconImagePathResolver|null
     */
    protected ?FaviconImagePathResolver $faviconImagePathResolver = null;

    /**
     * @return FaviconImagePathResolver
     */
    protected function createFaviconImagePathResolver(): FaviconImagePathResolver
    {
        return $this->faviconImagePathResolver ?: FaviconImagePathResolver::new();
    }

    /**
     * @param FaviconImagePathResolver $faviconImagePathResolver
     * @return static
     * @internal
     */
    public function setFaviconImagePathResolver(FaviconImagePathResolver $faviconImagePathResolver): static
    {
        $this->faviconImagePathResolver = $faviconImagePathResolver;
        return $this;
    }
}
