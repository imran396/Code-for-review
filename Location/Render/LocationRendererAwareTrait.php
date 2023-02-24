<?php
/**
 * Trait for Location Renderer
 *
 * SAM-4283: Location Renderer class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 05, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Location\Render;

/**
 * Trait LocationRendererAwareTrait
 * @package Sam\Location\Render
 */
trait LocationRendererAwareTrait
{
    /**
     * @var LocationRenderer|null
     */
    protected ?LocationRenderer $locationRenderer = null;

    /**
     * @return LocationRenderer
     */
    protected function getLocationRenderer(): LocationRenderer
    {
        if ($this->locationRenderer === null) {
            $this->locationRenderer = LocationRenderer::new();
        }
        return $this->locationRenderer;
    }

    /**
     * @param LocationRenderer $locationRenderer
     * @return static
     * @internal
     */
    public function setLocationRenderer(LocationRenderer $locationRenderer): static
    {
        $this->locationRenderer = $locationRenderer;
        return $this;
    }
}
