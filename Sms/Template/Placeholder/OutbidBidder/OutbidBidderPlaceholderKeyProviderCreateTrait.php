<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\OutbidBidder;

/**
 * Trait OutbidBidderPlaceholderKeyProviderCreateTrait
 * @package Sam\Sms\Template\Placeholder\OutbidBidder
 */
trait OutbidBidderPlaceholderKeyProviderCreateTrait
{
    protected ?OutbidBidderPlaceholderKeyProvider $outbidBidderPlaceholderKeyProvider = null;

    /**
     * @return OutbidBidderPlaceholderKeyProvider
     */
    protected function createOutbidBidderPlaceholderKeyProvider(): OutbidBidderPlaceholderKeyProvider
    {
        return $this->outbidBidderPlaceholderKeyProvider ?: OutbidBidderPlaceholderKeyProvider::new();
    }

    /**
     * @param OutbidBidderPlaceholderKeyProvider $outbidBidderPlaceholderKeyProvider
     * @return static
     * @internal
     */
    public function setOutbidBidderPlaceholderKeyProvider(OutbidBidderPlaceholderKeyProvider $outbidBidderPlaceholderKeyProvider): static
    {
        $this->outbidBidderPlaceholderKeyProvider = $outbidBidderPlaceholderKeyProvider;
        return $this;
    }
}
