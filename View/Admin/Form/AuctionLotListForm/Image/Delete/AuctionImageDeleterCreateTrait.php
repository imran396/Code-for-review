<?php
/**
 * SAM-7911: Refactor \LotImage_Deleter
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\Image\Delete;

/**
 * Trait AuctionImageDeleterCreateTrait
 * @package Sam\View\Admin\Form\AuctionLotListForm\Image\Delete
 */
trait AuctionImageDeleterCreateTrait
{
    protected ?AuctionImageDeleter $auctionImageDeleter = null;

    /**
     * @return AuctionImageDeleter
     */
    protected function createAuctionImageDeleter(): AuctionImageDeleter
    {
        return $this->auctionImageDeleter ?: AuctionImageDeleter::new();
    }

    /**
     * @param AuctionImageDeleter $auctionImageDeleter
     * @return static
     * @internal
     */
    public function setAuctionImageDeleter(AuctionImageDeleter $auctionImageDeleter): static
    {
        $this->auctionImageDeleter = $auctionImageDeleter;
        return $this;
    }
}
