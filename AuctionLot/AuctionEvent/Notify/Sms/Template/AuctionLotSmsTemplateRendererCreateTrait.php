<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\AuctionEvent\Notify\Sms\Template;

/**
 * Trait AuctionLotSmsTemplateRendererCreateTrait
 * @package Sam\AuctionLot\AuctionEvent\Notify\Sms\Template
 */
trait AuctionLotSmsTemplateRendererCreateTrait
{
    /**
     * @var AuctionLotSmsTemplateRenderer|null
     */
    protected ?AuctionLotSmsTemplateRenderer $auctionLotSmsTemplateRenderer = null;

    /**
     * @return AuctionLotSmsTemplateRenderer
     */
    protected function createAuctionLotSmsTemplateRenderer(): AuctionLotSmsTemplateRenderer
    {
        return $this->auctionLotSmsTemplateRenderer ?: AuctionLotSmsTemplateRenderer::new();
    }

    /**
     * @param AuctionLotSmsTemplateRenderer $auctionLotSmsTemplateRenderer
     * @return static
     * @internal
     */
    public function setAuctionLotSmsTemplateRenderer(AuctionLotSmsTemplateRenderer $auctionLotSmsTemplateRenderer): static
    {
        $this->auctionLotSmsTemplateRenderer = $auctionLotSmsTemplateRenderer;
        return $this;
    }
}
