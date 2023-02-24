<?php
/**
 * SAM-10981: Replace GET->POST for delete button at Admin Manage auctions page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Delete\Validate;

trait AuctionDeletionValidatorCreateTrait
{
    /**
     * @var AuctionDeletionValidator|null
     */
    protected ?AuctionDeletionValidator $auctionDeletionValidator = null;

    /**
     * @return AuctionDeletionValidator
     */
    protected function createAuctionDeletionValidator(): AuctionDeletionValidator
    {
        return $this->auctionDeletionValidator ?: AuctionDeletionValidator::new();
    }

    /**
     * @param AuctionDeletionValidator $auctionDeletionValidator
     * @return $this
     * @internal
     */
    public function setAuctionDeletionValidator(AuctionDeletionValidator $auctionDeletionValidator): static
    {
        $this->auctionDeletionValidator = $auctionDeletionValidator;
        return $this;
    }
}
