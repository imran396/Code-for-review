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

namespace Sam\Bidding\Notify\Sms;

/**
 * Trait OutbidBidderSmsNotifierCreateTrait
 * @package Sam\Bidding\Notify\Sms
 */
trait OutbidBidderSmsNotifierCreateTrait
{
    /**
     * @var OutbidBidderSmsNotifier|null
     */
    protected ?OutbidBidderSmsNotifier $outbidBidderSmsNotifier = null;

    /**
     * @return OutbidBidderSmsNotifier
     */
    protected function createOutbidBidderSmsNotifier(): OutbidBidderSmsNotifier
    {
        return $this->outbidBidderSmsNotifier ?: OutbidBidderSmsNotifier::new();
    }

    /**
     * @param OutbidBidderSmsNotifier $outbidBidderSmsNotifier
     * @return static
     * @internal
     */
    public function setOutbidBidderSmsNotifier(OutbidBidderSmsNotifier $outbidBidderSmsNotifier): static
    {
        $this->outbidBidderSmsNotifier = $outbidBidderSmsNotifier;
        return $this;
    }
}
