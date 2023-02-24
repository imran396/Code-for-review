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

namespace Sam\Image\Favicon\Load;


trait FaviconLoaderCreateTrait
{
    /**
     * @var FaviconLoader|null
     */
    protected ?FaviconLoader $faviconLoader = null;

    /**
     * @return FaviconLoader
     */
    protected function createFaviconLoader(): FaviconLoader
    {
        return $this->faviconLoader ?: FaviconLoader::new();
    }

    /**
     * @param FaviconLoader $faviconLoader
     * @return $this
     * @internal
     */
    public function setFaviconLoader(FaviconLoader $faviconLoader): static
    {
        $this->faviconLoader = $faviconLoader;
        return $this;
    }
}
