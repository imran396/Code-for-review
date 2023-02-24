<?php
/**
 * SAM-5041: Soap API RegisterBidder improvement
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\BidderNum\ChangeExisting;

/**
 * Trait BidderNumberApplierCreateTrait
 * @package Sam\Bidder\BidderNum\ChangeExisting
 */
trait ExistingBidderNumberChangerCreateTrait
{
    /**
     * @var ExistingBidderNumberChanger|null
     */
    protected ?ExistingBidderNumberChanger $existingBidderNumberChanger = null;

    /**
     * @return ExistingBidderNumberChanger
     */
    protected function createExistingBidderNumberChanger(): ExistingBidderNumberChanger
    {
        return $this->existingBidderNumberChanger ?: ExistingBidderNumberChanger::new();
    }

    /**
     * @param ExistingBidderNumberChanger|null $changer
     * @return $this
     * @internal
     */
    public function setExistingBidderNumberChanger(?ExistingBidderNumberChanger $changer): static
    {
        $this->existingBidderNumberChanger = $changer;
        return $this;
    }
}
