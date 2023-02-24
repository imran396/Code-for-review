<?php
/**
 * SAM-6595: Templated-content building - simplify module structure for v3.5
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 04, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Lot\Feed\Hint;

/**
 * Trait LotFeedHintRendererCreateTrait
 * @package Sam\Details\Lot\Feed\Hint
 */
trait LotFeedHintRendererCreateTrait
{
    /**
     * @var LotFeedHintRenderer|null
     */
    protected ?LotFeedHintRenderer $lotFeedHintRenderer = null;

    protected function createLotFeedHintRenderer(): LotFeedHintRenderer
    {
        return $this->lotFeedHintRenderer ?: LotFeedHintRenderer::new();
    }

    /**
     * @internal
     */
    public function setLotFeedHintRenderer(LotFeedHintRenderer $lotFeedHintRenderer): static
    {
        $this->lotFeedHintRenderer = $lotFeedHintRenderer;
        return $this;
    }
}
