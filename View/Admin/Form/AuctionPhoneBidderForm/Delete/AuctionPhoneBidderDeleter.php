<?php
/**
 * Auction Phone Bidder Deleter
 *
 * SAM-5817: Refactor data loader for Auction Phone Bidder page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 25, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionPhoneBidderForm\Delete;

use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\DeleteRepository\Entity\PhoneBidderDedicatedClerk\PhoneBidderDedicatedClerkDeleteRepositoryCreateTrait;

/**
 * Class AuctionPhoneBidderDeleter
 */
class AuctionPhoneBidderDeleter extends CustomizableClass
{
    use FilterAuctionAwareTrait;
    use PhoneBidderDedicatedClerkDeleteRepositoryCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete Phone Bidder Dedicated Clerks For Current Auction
     */
    public function delete(): void
    {
        $this->createPhoneBidderDedicatedClerkDeleteRepository()
            ->filterAuctionId($this->getFilterAuctionId())
            ->delete();
    }
}
