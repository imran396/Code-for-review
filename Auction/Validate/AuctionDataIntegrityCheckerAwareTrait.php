<?php
/**
 * Trait for AuctionDataIntegrityChecker
 *
 * SAM-5070: Data integrity checker - there shall only be one active auction_cust_data record for one auction
 * and one auction_cust_field
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           09/11/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Validate;

/**
 * Trait AuctionDataIntegrityCheckerAwareTrait
 * @package Sam\Auction\Validate
 */
trait AuctionDataIntegrityCheckerAwareTrait
{
    protected ?AuctionDataIntegrityChecker $auctionDataIntegrityChecker = null;

    /**
     * @return AuctionDataIntegrityChecker
     */
    protected function getAuctionDataIntegrityChecker(): AuctionDataIntegrityChecker
    {
        if ($this->auctionDataIntegrityChecker === null) {
            $this->auctionDataIntegrityChecker = AuctionDataIntegrityChecker::new();
        }
        return $this->auctionDataIntegrityChecker;
    }

    /**
     * @param AuctionDataIntegrityChecker $auctionDataIntegrityChecker
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionDataIntegrityChecker(AuctionDataIntegrityChecker $auctionDataIntegrityChecker): static
    {
        $this->auctionDataIntegrityChecker = $auctionDataIntegrityChecker;
        return $this;
    }
}
