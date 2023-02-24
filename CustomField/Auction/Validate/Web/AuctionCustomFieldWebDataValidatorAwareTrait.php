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
 * @since           Oct 3, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Validate\Web;

/**
 * Trait AuctionCustomFieldWebDataValidatorAwareTrait
 * @package Sam\CustomField\Auction\Validate\Web
 */
trait AuctionCustomFieldWebDataValidatorAwareTrait
{
    protected ?AuctionCustomWebDataValidator $auctionCustomFieldWebDataValidator = null;

    /**
     * @return AuctionCustomWebDataValidator
     */
    protected function getAuctionCustomFieldWebDataValidator(): AuctionCustomWebDataValidator
    {
        if ($this->auctionCustomFieldWebDataValidator === null) {
            $this->auctionCustomFieldWebDataValidator = AuctionCustomWebDataValidator::new();
        }
        return $this->auctionCustomFieldWebDataValidator;
    }

    /**
     * @param AuctionCustomWebDataValidator $auctionCustomFieldWebDataValidator
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionCustomFieldWebDataValidator(AuctionCustomWebDataValidator $auctionCustomFieldWebDataValidator): static
    {
        $this->auctionCustomFieldWebDataValidator = $auctionCustomFieldWebDataValidator;
        return $this;
    }
}
