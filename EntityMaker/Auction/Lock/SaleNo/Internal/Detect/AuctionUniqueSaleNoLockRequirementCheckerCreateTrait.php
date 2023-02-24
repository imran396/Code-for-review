<?php
/**
 * SAM-10615: Supply uniqueness of auction fields: sale#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 09, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Lock\SaleNo\Internal\Detect;

/**
 * Trait AuctionUniqueSaleNoLockRequirementCheckerCreateTrait
 * @package Sam\EntityMaker\Auction\Lock\SaleNo\Internal\Detect
 */
trait AuctionUniqueSaleNoLockRequirementCheckerCreateTrait
{
    protected ?AuctionUniqueSaleNoLockRequirementChecker $auctionUniqueSaleNoLockRequirementChecker = null;

    /**
     * @return AuctionUniqueSaleNoLockRequirementChecker
     */
    protected function createAuctionUniqueSaleNoLockRequirementChecker(): AuctionUniqueSaleNoLockRequirementChecker
    {
        return $this->auctionUniqueSaleNoLockRequirementChecker ?: AuctionUniqueSaleNoLockRequirementChecker::new();
    }

    /**
     * @param AuctionUniqueSaleNoLockRequirementChecker $auctionUniqueSaleNoLockRequirementChecker
     * @return static
     * @internal
     */
    public function setAuctionUniqueSaleNoLockRequirementChecker(AuctionUniqueSaleNoLockRequirementChecker $auctionUniqueSaleNoLockRequirementChecker): static
    {
        $this->auctionUniqueSaleNoLockRequirementChecker = $auctionUniqueSaleNoLockRequirementChecker;
        return $this;
    }
}
