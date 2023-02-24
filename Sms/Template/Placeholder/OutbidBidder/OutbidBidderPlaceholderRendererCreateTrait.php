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

namespace Sam\Sms\Template\Placeholder\OutbidBidder;

/**
 * Trait OutbidBidderPlaceholderRendererCreateTrait
 * @package Sam\Sms\Template\Placeholder\OutbidBidder
 */
trait OutbidBidderPlaceholderRendererCreateTrait
{
    protected ?OutbidBidderPlaceholderRenderer $outbidBidderPlaceholderRenderer = null;

    /**
     * @return OutbidBidderPlaceholderRenderer
     */
    protected function createOutbidBidderPlaceholderRenderer(): OutbidBidderPlaceholderRenderer
    {
        return $this->outbidBidderPlaceholderRenderer ?: OutbidBidderPlaceholderRenderer::new();
    }

    /**
     * @param OutbidBidderPlaceholderRenderer $outbidBidderPlaceholderRenderer
     * @return static
     * @internal
     */
    public function setOutbidBidderPlaceholderRenderer(OutbidBidderPlaceholderRenderer $outbidBidderPlaceholderRenderer): static
    {
        $this->outbidBidderPlaceholderRenderer = $outbidBidderPlaceholderRenderer;
        return $this;
    }
}
