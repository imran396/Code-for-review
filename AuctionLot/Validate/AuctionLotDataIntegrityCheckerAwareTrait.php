<?php
/**
 * Trait for AuctionLotDataIntegrityChecker
 *
 * SAM-5077: Avoid lot item be active in more than one auction
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           7/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Validate;

/**
 * Trait AuctionLotDataIntegrityCheckerAwareTrait
 * @package Sam\AuctionLot\Validate
 */
trait AuctionLotDataIntegrityCheckerAwareTrait
{
    /**
     * @var AuctionLotDataIntegrityChecker|null
     */
    protected ?AuctionLotDataIntegrityChecker $auctionLotDataIntegrityChecker = null;

    /**
     * @return AuctionLotDataIntegrityChecker
     */
    protected function getAuctionLotDataIntegrityChecker(): AuctionLotDataIntegrityChecker
    {
        if ($this->auctionLotDataIntegrityChecker === null) {
            $this->auctionLotDataIntegrityChecker = AuctionLotDataIntegrityChecker::new();
        }
        return $this->auctionLotDataIntegrityChecker;
    }

    /**
     * @param AuctionLotDataIntegrityChecker $auctionLotDataIntegrityChecker
     * @return static
     * @internal
     */
    public function setAuctionLotDataIntegrityChecker(AuctionLotDataIntegrityChecker $auctionLotDataIntegrityChecker): static
    {
        $this->auctionLotDataIntegrityChecker = $auctionLotDataIntegrityChecker;
        return $this;
    }
}
