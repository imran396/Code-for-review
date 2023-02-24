<?php
/**
 * Auction Custom Field List Data Loader Create Trait
 *
 * SAM-6440: Refactor auction custom field list page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionCustomFieldListForm\Load;

/**
 * Trait AuctionCustomFieldListDataLoaderCreateTrait
 */
trait AuctionCustomFieldListDataLoaderCreateTrait
{
    protected ?AuctionCustomFieldListDataLoader $auctionCustomFieldListDataLoader = null;

    /**
     * @return AuctionCustomFieldListDataLoader
     */
    protected function createAuctionCustomFieldListDataLoader(): AuctionCustomFieldListDataLoader
    {
        return $this->auctionCustomFieldListDataLoader ?: AuctionCustomFieldListDataLoader::new();
    }

    /**
     * @param AuctionCustomFieldListDataLoader $auctionCustomFieldListDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionCustomFieldListDataLoader(AuctionCustomFieldListDataLoader $auctionCustomFieldListDataLoader): static
    {
        $this->auctionCustomFieldListDataLoader = $auctionCustomFieldListDataLoader;
        return $this;
    }
}
