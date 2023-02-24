<?php
/**
 * SAM-6909: Refactor on-increment bid validator for v3.6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\OnIncrementBid\Internal\Render;

/**
 * Trait OnIncrementBidRendererCreateTrait
 * @package Sam\Bidding\OnIncrementBid\Render
 */
trait OnIncrementBidRendererCreateTrait
{
    protected ?OnIncrementBidRenderer $onIncrementBidRenderer = null;

    /**
     * @return OnIncrementBidRenderer
     */
    protected function createOnIncrementBidRenderer(): OnIncrementBidRenderer
    {
        return $this->onIncrementBidRenderer ?: OnIncrementBidRenderer::new();
    }

    /**
     * @param OnIncrementBidRenderer $onIncrementBidRenderer
     * @return $this
     * @internal
     */
    public function setOnIncrementBidRenderer(OnIncrementBidRenderer $onIncrementBidRenderer): static
    {
        $this->onIncrementBidRenderer = $onIncrementBidRenderer;
        return $this;
    }
}
