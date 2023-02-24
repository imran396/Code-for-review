<?php
/**
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 15, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Renderer;

/**
 * Trait BiddingHistoryLinkRendererCreateTrait
 */
trait BiddingHistoryLinkRendererCreateTrait
{
    protected ?BiddingHistoryLinkRenderer $biddingHistoryLinkRenderer = null;

    /**
     * @return BiddingHistoryLinkRenderer
     */
    protected function createBiddingHistoryLinkRenderer(): BiddingHistoryLinkRenderer
    {
        return $this->biddingHistoryLinkRenderer ?: BiddingHistoryLinkRenderer::new();
    }

    /**
     * @param BiddingHistoryLinkRenderer $renderer
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setBiddingHistoryLinkRenderer(BiddingHistoryLinkRenderer $renderer): static
    {
        $this->biddingHistoryLinkRenderer = $renderer;
        return $this;
    }
}
