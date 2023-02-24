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

namespace Sam\Details\Lot\SeoUrl\Hint;

/**
 * Trait LotSeoUrlHintRendererCreateTrait
 * @package Sam\Details\Lot\SeoUrl\Hint
 */
trait LotSeoUrlHintRendererCreateTrait
{
    /**
     * @var LotSeoUrlHintRenderer|null
     */
    protected ?LotSeoUrlHintRenderer $lotSeoUrlHintRenderer = null;

    protected function createLotSeoUrlHintRenderer(): LotSeoUrlHintRenderer
    {
        return $this->lotSeoUrlHintRenderer ?: LotSeoUrlHintRenderer::new();
    }

    /**
     * @internal
     */
    public function setLotSeoUrlHintRenderer(LotSeoUrlHintRenderer $lotSeoUrlHintRenderer): static
    {
        $this->lotSeoUrlHintRenderer = $lotSeoUrlHintRenderer;
        return $this;
    }
}
