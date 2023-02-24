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

namespace Sam\Details\Lot\Web\SeoMeta\Hint;

/**
 * Trait LotWebSeoMetaHintRendererCreateTrait
 * @package Sam\Details
 */
trait LotWebSeoMetaHintRendererCreateTrait
{
    /**
     * @var LotWebSeoMetaHintRenderer|null
     */
    protected ?LotWebSeoMetaHintRenderer $lotWebSeoMetaHintRenderer = null;

    protected function createLotWebSeoMetaHintRenderer(): LotWebSeoMetaHintRenderer
    {
        return $this->lotWebSeoMetaHintRenderer ?: LotWebSeoMetaHintRenderer::new();
    }

    /**
     * @internal
     */
    public function setLotWebSeoMetaHintRenderer(LotWebSeoMetaHintRenderer $lotWebSeoMetaHintRenderer): static
    {
        $this->lotWebSeoMetaHintRenderer = $lotWebSeoMetaHintRenderer;
        return $this;
    }
}
