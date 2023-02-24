<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\Render;

/**
 * Trait BidderRendererCreateTrait
 * @package Sam\Bidder\Render
 */
trait BidderRendererCreateTrait
{
    /**
     * @var BidderRenderer|null
     */
    protected ?BidderRenderer $bidderRenderer = null;

    /**
     * @return BidderRenderer
     */
    protected function createBidderRenderer(): BidderRenderer
    {
        return $this->bidderRenderer ?: BidderRenderer::new();
    }

    /**
     * @param BidderRenderer $bidderRenderer
     * @return static
     * @internal
     */
    public function setBidderRenderer(BidderRenderer $bidderRenderer): static
    {
        $this->bidderRenderer = $bidderRenderer;
        return $this;
    }
}
