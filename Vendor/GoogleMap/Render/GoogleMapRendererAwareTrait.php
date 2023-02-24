<?php
/**
 * SAM-4238: Google Map and Youtube render helper classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/5/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Vendor\GoogleMap\Render;

/**
 * Trait GoogleMapRendererAwareTrait
 * @package Sam\Vendor\GoogleMap\Render
 */
trait GoogleMapRendererAwareTrait
{
    protected ?GoogleMapRenderer $googleMapRenderer = null;

    /**
     * @return GoogleMapRenderer
     */
    public function getGoogleMapRenderer(): GoogleMapRenderer
    {
        if ($this->googleMapRenderer === null) {
            $this->googleMapRenderer = GoogleMapRenderer::new();
        }
        return $this->googleMapRenderer;
    }

    /**
     * @param GoogleMapRenderer $googleMapRenderer
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setGoogleMapRenderer(GoogleMapRenderer $googleMapRenderer): static
    {
        $this->googleMapRenderer = $googleMapRenderer;
        return $this;
    }
}
