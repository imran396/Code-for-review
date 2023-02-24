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

namespace Sam\Sms\Template\Placeholder\AuctionLot;

/**
 * Trait AuctionLotPlaceholderRendererCreateTrait
 * @package Sam\Sms\Template\Placeholder\AuctionLot
 */
trait AuctionLotPlaceholderRendererCreateTrait
{
    protected ?AuctionLotPlaceholderRenderer $auctionLotPlaceholderRenderer = null;

    /**
     * @return AuctionLotPlaceholderRenderer
     */
    protected function createAuctionLotPlaceholderRenderer(): AuctionLotPlaceholderRenderer
    {
        return $this->auctionLotPlaceholderRenderer ?: AuctionLotPlaceholderRenderer::new();
    }

    /**
     * @param AuctionLotPlaceholderRenderer $auctionLotPlaceholderRenderer
     * @return static
     * @internal
     */
    public function setAuctionLotPlaceholderRenderer(AuctionLotPlaceholderRenderer $auctionLotPlaceholderRenderer): static
    {
        $this->auctionLotPlaceholderRenderer = $auctionLotPlaceholderRenderer;
        return $this;
    }
}
