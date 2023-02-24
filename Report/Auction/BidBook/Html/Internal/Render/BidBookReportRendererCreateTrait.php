<?php
/**
 * SAM-5753: Refactor "Bid Book" report
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\BidBook\Html\Internal\Render;

/**
 * Trait BidBookReportRendererCreateTrait
 * @package Sam\Report\Auction\BidBook\Html\Internal\Render
 */
trait BidBookReportRendererCreateTrait
{
    protected ?BidBookReportRenderer $bidBookReportRenderer = null;

    /**
     * @return BidBookReportRenderer
     */
    protected function createBidBookReportRenderer(): BidBookReportRenderer
    {
        return $this->bidBookReportRenderer ?: BidBookReportRenderer::new();
    }

    /**
     * @param BidBookReportRenderer $bidBookReportRenderer
     * @return static
     * @internal
     */
    public function setBidBookReportRenderer(BidBookReportRenderer $bidBookReportRenderer): static
    {
        $this->bidBookReportRenderer = $bidBookReportRenderer;
        return $this;
    }
}
