<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/28/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Register\Notify;

/**
 * Trait NotifierAwareTrait
 * @package Sam\Bidder\AuctionBidder\Register\Notify
 */
trait NotifierAwareTrait
{
    /**
     * @var Notifier|null
     */
    private ?Notifier $notifier = null;

    /**
     * @return Notifier
     */
    protected function getNotifier(): Notifier
    {
        if ($this->notifier === null) {
            $this->notifier = Notifier::new();
        }
        return $this->notifier;
    }

    /**
     * @param Notifier $auctionBidderRegistrationNotifier
     * @return static
     */
    public function setNotifier(Notifier $auctionBidderRegistrationNotifier): static
    {
        $this->notifier = $auctionBidderRegistrationNotifier;
        return $this;
    }
}
