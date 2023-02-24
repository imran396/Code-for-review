<?php
/**
 * SAM-6740: "Preview in auction" adjustments for lot item preview link
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoPanel\PreviewInAuction;

/**
 * Trait PreviewInAuctionRendererCreateTrait
 * @package Sam\View\Admin\Form\LotInfoPanel\PreviewInAuction
 */
trait PreviewInAuctionRendererCreateTrait
{
    protected ?PreviewInAuctionRenderer $previewInAuctionRenderer = null;

    /**
     * @return PreviewInAuctionRenderer
     */
    public function createPreviewInAuctionRenderer(): PreviewInAuctionRenderer
    {
        return $this->previewInAuctionRenderer ?: PreviewInAuctionRenderer::new();
    }

    /**
     * @param PreviewInAuctionRenderer $previewInAuctionRenderer
     * @return $this
     * @interanl
     */
    public function setPreviewInAuctionRenderer(PreviewInAuctionRenderer $previewInAuctionRenderer): static
    {
        $this->previewInAuctionRenderer = $previewInAuctionRenderer;
        return $this;
    }
}
