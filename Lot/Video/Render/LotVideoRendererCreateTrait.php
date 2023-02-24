<?php
/**
 * SAM-6753: Structural adjustments for image path construction logic
 * ARP-2: Video Custom field to be placed with the photo - @link https://bidpath.atlassian.net/browse/ARP-2
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 17, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Video\Render;

/**
 * Trait LotVideoRendererCreateTrait
 * @package Sam\Lot\Video\Render
 */
trait LotVideoRendererCreateTrait
{
    /**
     * @var LotVideoRenderer|null
     */
    protected ?LotVideoRenderer $lotVideoRenderer = null;

    /**
     * @return LotVideoRenderer
     */
    protected function createLotVideoRenderer(): LotVideoRenderer
    {
        return $this->lotVideoRenderer ?: LotVideoRenderer::new();
    }

    /**
     * @param LotVideoRenderer $lotVideoRenderer
     * @return $this
     * @internal
     */
    public function setLotVideoRenderer(LotVideoRenderer $lotVideoRenderer): static
    {
        $this->lotVideoRenderer = $lotVideoRenderer;
        return $this;
    }
}
