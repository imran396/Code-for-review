<?php
/**
 * Auction Phone Bidder Assign Clerk Updater
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

use Sam\Bidding\AbsenteeBid\Load\AbsenteeBidLoaderAwareTrait;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\AbsenteeBid\AbsenteeBidWriteRepositoryAwareTrait;

/**
 * Class AuctionPhoneBidderAssignedClerkUpdater
 */
class AuctionPhoneBidderAssignedClerkUpdater extends CustomizableClass
{
    use AbsenteeBidLoaderAwareTrait;
    use AbsenteeBidWriteRepositoryAwareTrait;

    protected ?int $absenteeBidId = null;
    protected string $assignedClerkText = '';

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $absenteeBidId
     * @return $this
     */
    public function setAbsenteeBidId(int $absenteeBidId): static
    {
        $this->absenteeBidId = Cast::toInt($absenteeBidId, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @param string $assignedClerkText
     * @return $this
     */
    public function setAssignedClerkText(string $assignedClerkText): static
    {
        $this->assignedClerkText = $assignedClerkText;
        return $this;
    }

    /**
     * @return string
     */
    public function getAssignedClerkText(): string
    {
        return $this->assignedClerkText;
    }

    /**
     * @return int|null
     */
    public function getAbsenteeBidId(): ?int
    {
        return $this->absenteeBidId;
    }

    /**
     * Assign Phone Clerk
     * @param int $editorUserId
     */
    public function update(int $editorUserId): void
    {
        $absenteeBid = $this->getAbsenteeBidLoader()->loadById($this->getAbsenteeBidId());
        if (!$absenteeBid) {
            log_error(
                "Available absentee bid not found"
                . composeSuffix(['ab' => $this->getAbsenteeBidId()])
            );
            return;
        }
        $absenteeBid->AssignedClerk = $this->getAssignedClerkText();
        $this->getAbsenteeBidWriteRepository()->saveWithModifier($absenteeBid, $editorUserId);
    }
}
