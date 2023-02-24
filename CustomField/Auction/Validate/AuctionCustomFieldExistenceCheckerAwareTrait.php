<?php
/**
 * Help methods for auction custom field data loading
 *
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 11, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Validate;

/**
 * Trait AuctionCustomFieldExistenceCheckerAwareTrait
 * @package Sam\CustomField\Auction\Validate
 */
trait AuctionCustomFieldExistenceCheckerAwareTrait
{
    protected ?AuctionCustomFieldExistenceChecker $auctionCustomFieldExistenceChecker = null;

    /**
     * @return AuctionCustomFieldExistenceChecker
     */
    protected function getAuctionCustomFieldExistenceChecker(): AuctionCustomFieldExistenceChecker
    {
        if ($this->auctionCustomFieldExistenceChecker === null) {
            $this->auctionCustomFieldExistenceChecker = AuctionCustomFieldExistenceChecker::new();
        }
        return $this->auctionCustomFieldExistenceChecker;
    }

    /**
     * @param AuctionCustomFieldExistenceChecker $auctionCustomFieldExistenceChecker
     * @return static
     * @internal
     */
    public function setAuctionCustomFieldExistenceChecker(AuctionCustomFieldExistenceChecker $auctionCustomFieldExistenceChecker): static
    {
        $this->auctionCustomFieldExistenceChecker = $auctionCustomFieldExistenceChecker;
        return $this;
    }
}
