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
 * @since           Oct 7, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Load;

/**
 * Trait AuctionCustomFieldLoaderAwareTrait
 * @package Sam\CustomField\Auction\Load
 */
trait AuctionCustomFieldLoaderAwareTrait
{
    protected ?AuctionCustomFieldLoader $auctionCustomFieldLoader = null;

    /**
     * @return AuctionCustomFieldLoader
     */
    protected function getAuctionCustomFieldLoader(): AuctionCustomFieldLoader
    {
        if ($this->auctionCustomFieldLoader === null) {
            $this->auctionCustomFieldLoader = AuctionCustomFieldLoader::new();
        }
        return $this->auctionCustomFieldLoader;
    }

    /**
     * @param AuctionCustomFieldLoader $auctionCustomFieldLoader
     * @return static
     * @internal
     */
    public function setAuctionCustomFieldLoader(AuctionCustomFieldLoader $auctionCustomFieldLoader): static
    {
        $this->auctionCustomFieldLoader = $auctionCustomFieldLoader;
        return $this;
    }
}
