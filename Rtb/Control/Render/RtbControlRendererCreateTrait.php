<?php
/**
 * SAM-6489: Rtb console control rendering at server side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 06, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Control\Render;

/**
 * Trait RtbControlRendererCreateTrait
 * @package Sam\Rtb\Control\Render
 */
trait RtbControlRendererCreateTrait
{
    /**
     * @var RtbControlRenderer|null
     */
    protected ?RtbControlRenderer $rtbControlRenderer = null;

    /**
     * @return RtbControlRenderer
     */
    protected function createRtbControlRenderer(): RtbControlRenderer
    {
        return $this->rtbControlRenderer ?: RtbControlRenderer::new();
    }

    /**
     * @param RtbControlRenderer $rtbControlRenderer
     * @return $this
     * @internal
     */
    public function setRtbControlRenderer(RtbControlRenderer $rtbControlRenderer): static
    {
        $this->rtbControlRenderer = $rtbControlRenderer;
        return $this;
    }
}
