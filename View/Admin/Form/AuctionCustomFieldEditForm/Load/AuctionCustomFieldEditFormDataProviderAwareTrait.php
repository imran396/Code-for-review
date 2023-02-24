<?php
/**
 * SAM-6585: Refactor auction custom field management to separate module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Ivan Zgoniaiko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionCustomFieldEditForm\Load;

/**
 * Trait AuctionCustomFieldEditFormDataProviderAwareTrait
 * @package
 */
trait AuctionCustomFieldEditFormDataProviderAwareTrait
{
    protected ?AuctionCustomFieldEditFormDataProvider $auctionCustomFieldEditFormDataProvider = null;

    /**
     * @return AuctionCustomFieldEditFormDataProvider
     */
    protected function getAuctionCustomFieldEditFormDataProvider(): AuctionCustomFieldEditFormDataProvider
    {
        if ($this->auctionCustomFieldEditFormDataProvider === null) {
            $this->auctionCustomFieldEditFormDataProvider = AuctionCustomFieldEditFormDataProvider::new();
        }
        return $this->auctionCustomFieldEditFormDataProvider;
    }

    /**
     * @param AuctionCustomFieldEditFormDataProvider $auctionCustomFieldEditFormDataProvider
     * @return $this
     * @internal
     */
    public function setAuctionCustomFieldEditFormDataProvider(
        AuctionCustomFieldEditFormDataProvider $auctionCustomFieldEditFormDataProvider
    ): static {
        $this->auctionCustomFieldEditFormDataProvider = $auctionCustomFieldEditFormDataProvider;
        return $this;
    }
}
