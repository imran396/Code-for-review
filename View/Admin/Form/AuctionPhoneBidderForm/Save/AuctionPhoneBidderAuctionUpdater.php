<?php
/**
 * Auction Phone Bidder Auction Updater
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

namespace Sam\View\Admin\Form\AuctionPhoneBidderForm\Save;

use Auction;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;

/**
 * Class AuctionPhoneBidderAuctionUpdater
 * @method Auction getAuction()
 */
class AuctionPhoneBidderAuctionUpdater extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionWriteRepositoryAwareTrait;

    protected int $maxClerks = 1;
    protected int $lotSpacing = 0;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $maxClerks
     * @return $this
     */
    public function setMaxClerks(int $maxClerks): static
    {
        $this->maxClerks = $maxClerks;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxClerks(): int
    {
        return $this->maxClerks;
    }

    /**
     * @param int|null $lotSpacing null means empty value of lot spacing
     * @return $this
     */
    public function setLotSpacing(?int $lotSpacing): static
    {
        $this->lotSpacing = (int)$lotSpacing;
        return $this;
    }

    /**
     * @return int
     */
    public function getLotSpacing(): int
    {
        return $this->lotSpacing;
    }

    /**
     * Update Max Clerks and Lot Spacing for Auction
     * @param int $editorUserId
     */
    public function update(int $editorUserId): void
    {
        $auction = $this->getAuction();
        $auction->MaxClerk = $this->getMaxClerks();
        $auction->LotSpacing = $this->getLotSpacing();
        $this->getAuctionWriteRepository()->saveWithModifier($auction, $editorUserId);
    }
}
