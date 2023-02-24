<?php
/**
 * Helper methods for House Bidder feature
 *
 * SAM-2991: Bidonfusion - House bidder features
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Aug 15, 2015
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\HouseBidder;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;
use Sam\User\Privilege\Validate\BidderPrivilegeChecker;

/**
 * Class Helper
 * @package Sam\Bidder\HouseBidder
 */
class Helper extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotItemWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Mark auction lots as "Unsold", that have winner with "House Bidder" privilege
     * and clean sold info of lot items
     * @param int $auctionId
     * @param int $editorUserId
     * @return void
     */
    public function unsoldLotsForHouseBidders(int $auctionId, int $editorUserId): void
    {
        $repo = $this->createAuctionLotItemReadRepository()
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses);
        $hasPrivilegesForHouseBidder = [];
        $houseBidderLotItemIds = [];
        $offset = 0;
        $limit = 100;
        do {
            $auctionLots = $repo->limit($limit)
                ->offset($offset)
                ->loadEntities();
            foreach ($auctionLots as $auctionLot) {
                $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId, true);
                if (!$lotItem) {
                    log_error(
                        "Available lot item not found, when marking lot unsold for house bidder"
                        . composeSuffix(['li' => $auctionLot->LotItemId, 'ali' => $auctionLot->Id])
                    );
                    return;
                }
                $userId = $lotItem->WinningBidderId;
                if ($userId) {
                    if (!isset($hasPrivilegesForHouseBidder[$userId])) {
                        $hasPrivilegesForHouseBidder[$userId] = BidderPrivilegeChecker::new()
                            ->enableReadOnlyDb(true)
                            ->initByUserId($userId)
                            ->hasPrivilegeForHouse();
                    }
                    if ($hasPrivilegesForHouseBidder[$userId]) {
                        $lotItem->wipeOutSoldInfo();
                        $this->getLotItemWriteRepository()->saveWithModifier($lotItem, $editorUserId);
                        $auctionLot->toUnsold();
                        $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
                        $houseBidderLotItemIds[] = $lotItem->Id;
                    }
                }
            }
            $offset += $limit;
        } while (count($auctionLots));
        if (count($houseBidderLotItemIds)) {
            log_info(
                'Lots sold to House Bidders are marked unsold and their sold info is wiped out' .
                composeSuffix(['li' => $houseBidderLotItemIds])
            );
        }
    }
}
