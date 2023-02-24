<?php
/**
 * SAM-6595: Templated-content building - simplify module structure for v3.5
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 03, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Lot\Web\Rtb\Hint;

/**
 * Trait LotWebRtbHintRendererCreateTrait
 * @package Sam\Details
 */
trait LotWebRtbHintRendererCreateTrait
{
    /**
     * @var LotWebRtbHintRenderer|null
     */
    protected ?LotWebRtbHintRenderer $lotWebRtbHintRenderer = null;

    protected function createLotWebRtbHintRenderer(): LotWebRtbHintRenderer
    {
        return $this->lotWebRtbHintRenderer ?: LotWebRtbHintRenderer::new();
    }

    /**
     * @internal
     */
    public function setLotWebRtbHintRenderer(LotWebRtbHintRenderer $lotWebRtbHintRenderer): static
    {
        $this->lotWebRtbHintRenderer = $lotWebRtbHintRenderer;
        return $this;
    }
}
