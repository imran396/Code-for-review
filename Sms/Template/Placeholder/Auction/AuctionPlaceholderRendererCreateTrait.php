<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\Auction;

/**
 * Trait AuctionPlaceholderRendererCreateTrait
 * @package Sam\Sms\Template\Placeholder\Auction
 */
trait AuctionPlaceholderRendererCreateTrait
{
    protected ?AuctionPlaceholderRenderer $auctionPlaceholderRenderer = null;

    /**
     * @return AuctionPlaceholderRenderer
     */
    protected function createAuctionPlaceholderRenderer(): AuctionPlaceholderRenderer
    {
        return $this->auctionPlaceholderRenderer ?: AuctionPlaceholderRenderer::new();
    }

    /**
     * @param AuctionPlaceholderRenderer $auctionPlaceholderRenderer
     * @return static
     * @internal
     */
    public function setAuctionPlaceholderRenderer(AuctionPlaceholderRenderer $auctionPlaceholderRenderer): static
    {
        $this->auctionPlaceholderRenderer = $auctionPlaceholderRenderer;
        return $this;
    }
}
