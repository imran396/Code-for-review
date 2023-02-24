<?php
/**
 * SAM-10226: Refactor bidder dashboard tooltip for v3-7
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\BidderDashboard\Tooltip;

/**
 * Trait BidderDashboardTooltipRendererCreateTrait
 * @package Sam\View\Admin\ViewHelper\BidderDashboard\Tooltip
 */
trait BidderDashboardTooltipRendererCreateTrait
{
    protected ?BidderDashboardTooltipRenderer $bidderDashboardTooltipRenderer = null;

    /**
     * @return BidderDashboardTooltipRenderer
     */
    protected function createBidderDashboardTooltipRenderer(): BidderDashboardTooltipRenderer
    {
        return $this->bidderDashboardTooltipRenderer ?: BidderDashboardTooltipRenderer::new();
    }

    /**
     * @param BidderDashboardTooltipRenderer $bidderDashboardTooltipRenderer
     * @return $this
     * @internal
     */
    public function setBidderDashboardTooltipRenderer(BidderDashboardTooltipRenderer $bidderDashboardTooltipRenderer): static
    {
        $this->bidderDashboardTooltipRenderer = $bidderDashboardTooltipRenderer;
        return $this;
    }
}
