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
 * @since           Oct 19, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Help;

/**
 * Trait AuctionCustomFieldHelperAwareTrait
 * @package Sam\CustomField\Auction\Help
 */
trait AuctionCustomFieldHelperAwareTrait
{
    protected ?AuctionCustomFieldHelper $auctionCustomFieldHelper = null;

    /**
     * @return AuctionCustomFieldHelper
     */
    protected function getAuctionCustomFieldHelper(): AuctionCustomFieldHelper
    {
        if ($this->auctionCustomFieldHelper === null) {
            $this->auctionCustomFieldHelper = AuctionCustomFieldHelper::new();
        }
        return $this->auctionCustomFieldHelper;
    }

    /**
     * @param AuctionCustomFieldHelper $auctionCustomFieldHelper
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionCustomFieldHelper(AuctionCustomFieldHelper $auctionCustomFieldHelper): static
    {
        $this->auctionCustomFieldHelper = $auctionCustomFieldHelper;
        return $this;
    }
}
