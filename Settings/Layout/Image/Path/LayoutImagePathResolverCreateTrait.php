<?php
/**
 * SAM-9458: Path resolving for logo images of pages layout
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Layout\Image\Path;

/**
 * Trait LayoutImagePathResolverCreateTrait
 * @package Sam\Settings\Layout\Image\Path
 */
trait LayoutImagePathResolverCreateTrait
{
    protected ?LayoutImagePathResolver $layoutImagePathResolver = null;

    /**
     * @return LayoutImagePathResolver
     */
    protected function createLayoutImagePathResolver(): LayoutImagePathResolver
    {
        return $this->layoutImagePathResolver ?: LayoutImagePathResolver::new();
    }

    /**
     * @param LayoutImagePathResolver $layoutImagePathResolver
     * @return $this
     * @internal
     */
    public function setLayoutImagePathResolver(LayoutImagePathResolver $layoutImagePathResolver): static
    {
        $this->layoutImagePathResolver = $layoutImagePathResolver;
        return $this;
    }
}
