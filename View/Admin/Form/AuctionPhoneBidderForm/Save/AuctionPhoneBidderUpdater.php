<?php
/**
 * Auction Phone Bidder Updater
 *
 * SAM-5817: Refactor data loader for Auction Phone Bidder page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 19, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionPhoneBidderForm\Save;

use Sam\Bidding\AbsenteeBid\Load\AbsenteeBidLoaderAwareTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\PhoneBidderDedicatedClerk\PhoneBidderDedicatedClerkWriteRepositoryAwareTrait;

/**
 * Class AuctionPhoneBidderUpdater
 */
class AuctionPhoneBidderUpdater extends CustomizableClass
{
    use AbsenteeBidLoaderAwareTrait;
    use CurrentDateTrait;
    use EditorUserAwareTrait;
    use EntityFactoryCreateTrait;
    use FilterAuctionAwareTrait;
    use PhoneBidderDedicatedClerkWriteRepositoryAwareTrait;

    protected string $assignedClerk = '';
    protected ?int $bidderId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $bidderId
     * @return $this
     */
    public function setBidderId(int $bidderId): static
    {
        $this->bidderId = $bidderId;
        return $this;
    }

    /**
     * @return int
     */
    public function getBidderId(): int
    {
        return $this->bidderId;
    }

    /**
     * @param string $assignedClerk
     * @return $this
     */
    public function setAssignedClerk(string $assignedClerk): static
    {
        $this->assignedClerk = $assignedClerk;
        return $this;
    }

    /**
     * @return string
     */
    public function getAssignedClerk(): string
    {
        return $this->assignedClerk;
    }

    /**
     * Save Phone Bidders Clerks Changes
     */
    public function update(): void
    {
        $dedicatedClerk = $this->createEntityFactory()->phoneBidderDedicatedClerk();
        $dedicatedClerk->AuctionId = $this->getFilterAuctionId();
        $dedicatedClerk->AssignedClerk = $this->getAssignedClerk();
        $dedicatedClerk->BidderId = $this->getBidderId();
        $this->getPhoneBidderDedicatedClerkWriteRepository()->saveWithModifier($dedicatedClerk, $this->getEditorUserId());
    }
}
