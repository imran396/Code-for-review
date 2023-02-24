<?php
/**
 * SAM-5344: Rtb rendering helper
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           8/11/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Render;

/**
 * Trait RtbRendererAwareTrait
 */
trait RtbRendererCreateTrait
{
    /**
     * @var RtbRenderer|null
     */
    protected ?RtbRenderer $rtbRenderer = null;

    /**
     * @return RtbRenderer
     */
    protected function createRtbRenderer(): RtbRenderer
    {
        $rtbRenderer = $this->rtbRenderer ?: RtbRenderer::new();
        return $rtbRenderer;
    }

    /**
     * @param RtbRenderer $rtbRenderer
     * @return static
     * @internal
     */
    public function setRtbRenderer(RtbRenderer $rtbRenderer): static
    {
        $this->rtbRenderer = $rtbRenderer;
        return $this;
    }
}
