<?php
/**
 * SAM-5817: Refactor data loader for Auction Phone Bidder page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 04, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionPhoneBidderForm\Save;

use AbsenteeBid;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepository;
use Sam\Storage\WriteRepository\Entity\AbsenteeBid\AbsenteeBidWriteRepositoryAwareTrait;

/**
 * Class AuctionPhoneBidderAssignedClerkResetter
 * @package Sam\View\Admin\Form\AuctionPhoneBidderForm\Save
 */
class AuctionPhoneBidderAssignedClerkResetter extends CustomizableClass
{
    use AbsenteeBidWriteRepositoryAwareTrait;
    use OptionalsTrait;

    public const OP_ABSENTEE_BIDS = 'absenteeBids';

    // Outgoing values

    /**
     * @var AbsenteeBid[]
     */
    private array $affectedAbsenteeBids = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Reset Assigned Phone Clerk
     * @param int $auctionId
     * @param int $editorUserId
     */
    public function reset(int $auctionId, int $editorUserId): void
    {
        $absenteeBids = $this->fetchOptional(self::OP_ABSENTEE_BIDS, [$auctionId]);
        foreach ($absenteeBids as $absenteeBid) {
            $absenteeBid->AssignedClerk = '';
            $this->getAbsenteeBidWriteRepository()->saveWithModifier($absenteeBid, $editorUserId);
        }
        $this->affectedAbsenteeBids = $absenteeBids;
    }

    /**
     * @return AbsenteeBid[]
     */
    public function affectedAbsenteeBids(): array
    {
        return $this->affectedAbsenteeBids;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_ABSENTEE_BIDS] = $optionals[self::OP_ABSENTEE_BIDS]
            ?? static function (int $auctionId) {
                return AbsenteeBidReadRepository::new()
                    ->filterAuctionId($auctionId)
                    ->joinAccountFilterActive(true)
                    ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
                    ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
                    ->joinLotItemFilterActive(true)
                    ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
                    ->loadEntities();
            };
        $this->setOptionals($optionals);
    }
}
