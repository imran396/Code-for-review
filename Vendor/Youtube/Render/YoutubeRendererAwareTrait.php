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

namespace Sam\Vendor\Youtube\Render;

/**
 * Trait YoutubeRendererAwareTrait
 * @package Sam\Vendor\Youtube\Render
 */
trait YoutubeRendererAwareTrait
{
    protected ?YoutubeRenderer $youtubeRenderer = null;

    /**
     * @return YoutubeRenderer
     */
    public function getYoutubeRenderer(): YoutubeRenderer
    {
        if ($this->youtubeRenderer === null) {
            $this->youtubeRenderer = YoutubeRenderer::new();
        }
        return $this->youtubeRenderer;
    }

    /**
     * @param YoutubeRenderer $youtubeRenderer
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setYoutubeRenderer(YoutubeRenderer $youtubeRenderer): static
    {
        $this->youtubeRenderer = $youtubeRenderer;
        return $this;
    }
}
