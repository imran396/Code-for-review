<?php
/**
 * SAM-4039: Auction deleter class
 * SAM-6671: Auction deleter for v3.5
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Delete;

/**
 * Trait AuctionCustomFieldDeleterCreateTrait
 * @package Sam\CustomField\Auction\Delete
 */
trait AuctionCustomFieldDeleterCreateTrait
{
    protected ?AuctionCustomFieldDeleter $auctionCustomFieldDeleter = null;

    /**
     * @return AuctionCustomFieldDeleter
     */
    protected function createAuctionCustomFieldDeleter(): AuctionCustomFieldDeleter
    {
        return $this->auctionCustomFieldDeleter ?: AuctionCustomFieldDeleter::new();
    }

    /**
     * @param AuctionCustomFieldDeleter $auctionCustomFieldDeleter
     * @return $this
     * @internal
     */
    public function setAuctionCustomFieldDeleter(AuctionCustomFieldDeleter $auctionCustomFieldDeleter): static
    {
        $this->auctionCustomFieldDeleter = $auctionCustomFieldDeleter;
        return $this;
    }
}
