<?php
/**
 * SAM-6434: Refactor auction image logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Image\Upload;

/**
 * Trait AuctionImageUploaderCreateTrait
 * @package Sam\Auction\Image\Upload
 */
trait AuctionImageUploaderCreateTrait
{
    protected ?AuctionImageUploader $auctionImageUploader = null;

    /**
     * @return AuctionImageUploader
     */
    protected function createAuctionImageUploader(): AuctionImageUploader
    {
        return $this->auctionImageUploader ?: AuctionImageUploader::new();
    }

    /**
     * @param AuctionImageUploader $auctionImageUploader
     * @return $this
     * @internal
     */
    public function setAuctionImageUploader(AuctionImageUploader $auctionImageUploader): static
    {
        $this->auctionImageUploader = $auctionImageUploader;
        return $this;
    }
}
