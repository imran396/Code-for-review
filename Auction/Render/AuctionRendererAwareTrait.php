<?php
/**
 * Trait for Auction Renderer
 *
 * SAM-3919: Auction Renderer class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 13, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Render;

/**
 * Trait AuctionRendererAwareTrait
 * @package Sam\Auction\Render
 */
trait AuctionRendererAwareTrait
{
    protected ?AuctionRendererInterface $auctionRenderer = null;

    /**
     * @return AuctionRendererInterface
     */
    protected function getAuctionRenderer(): AuctionRendererInterface
    {
        if ($this->auctionRenderer === null) {
            $this->auctionRenderer = AuctionRenderer::new();
        }
        return $this->auctionRenderer;
    }

    /**
     * @param AuctionRendererInterface $auctionRenderer
     * @return static
     * @internal
     */
    public function setAuctionRenderer(AuctionRendererInterface $auctionRenderer): static
    {
        $this->auctionRenderer = $auctionRenderer;
        return $this;
    }
}
