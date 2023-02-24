<?php
/**
 * This class used to find sold lot and if sold lot winner bidder privilege is "HOUSE BIDDER' then makes it unsold and wipe out sold information.
 *
 * SAM-7685 : Implement house bidder feature execution on scheduled sale closing
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Close\Live;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;
use Sam\User\Privilege\Validate\BidderPrivilegeCheckerAwareTrait;

/**
 * Class LiveLotCloser
 * @package Sam\Auction\Close\Live
 */
class LiveLotCloser extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use BidderPrivilegeCheckerAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotItemWriteRepositoryAwareTrait;

    private const CHUNK_SIZE = 100;

    /**
     * @var array
     */
    protected array $houseBidderLotItemIds = [];
    /**
     * @var array
     */
    protected array $hasPrivilegesForHouseBidder = [];
    /***
     * @var array
     */
    protected array $wipeOutLotItems = [];
    /**
     * @var array
     */
    protected array $unsoldAuctionLots = [];

    /**
     * Class instantiation method
     * @return $this
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
    public function close(int $auctionId, int $editorUserId): void
    {
        $repo = $this->createAuctionLotItemReadRepository()
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::LS_SOLD);
        $offset = 0;
        $limit = self::CHUNK_SIZE;
        do {
            $auctionLots = $repo->limit($limit)
                ->offset($offset)
                ->loadEntities();
            if ($auctionLots) {
                $this->unsoldAuctionLots($auctionLots, $editorUserId);
            }
            $offset += $limit;
        } while (count($auctionLots));

        $this->logHouseBidderLotItemInfo();
    }

    /**
     * @param array $auctionLots
     * @param int $editorUserId
     */
    protected function unsoldAuctionLots(array $auctionLots, int $editorUserId): void
    {
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
            $hammerPrice = $lotItem->HammerPrice;
            $soldDate = $lotItem->DateSold;
            $internetBid = $lotItem->InternetBid;

            if (
                $userId
                && $this->checkHouseBidderPrivilege($userId)
            ) {
                $lotItem->wipeOutSoldInfo();
                $this->getLotItemWriteRepository()->saveWithModifier($lotItem, $editorUserId);
                $this->wipeOutLotItems[] = $lotItem;

                $auctionLot->toUnsold();
                $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
                $this->unsoldAuctionLots[] = $auctionLot;
                $this->houseBidderLotItemIds[] = $lotItem->Id;

                log_info(
                    'Wipe out winning bidder info, because winner has House Bidder privilege' . composeSuffix(
                        [
                            'li' => $lotItem->Id,
                            'winner' => $userId,
                            'hp' => $hammerPrice,
                            'sale sold' => $lotItem->AuctionId,
                            'date sold' => $soldDate,
                            'internet bid' => $internetBid
                        ]
                    )
                );
            }
        }
    }

    /**
     * @param int $userId
     * @return bool
     */
    protected function checkHouseBidderPrivilege(int $userId): bool
    {
        if (!isset($this->hasPrivilegesForHouseBidder[$userId])) {
            $hasHouseBidderPrivilege = $this->getBidderPrivilegeChecker()
                ->enableReadOnlyDb(true)
                ->initByUserId($userId)
                ->hasPrivilegeForHouse();
            $this->hasPrivilegesForHouseBidder[$userId] = $hasHouseBidderPrivilege;
        }
        return $this->hasPrivilegesForHouseBidder[$userId];
    }

    /**
     * @return array
     * @internal for unit testing
     */
    public function getWipeOutLotItems(): array
    {
        return $this->wipeOutLotItems;
    }

    /**
     * @return array
     * @internal for unit testing
     */
    public function getUnsoldAuctionLots(): array
    {
        return $this->unsoldAuctionLots;
    }

    protected function logHouseBidderLotItemInfo(): void
    {
        if (count($this->houseBidderLotItemIds)) {
            log_info(
                'Lots sold to House Bidders are marked unsold and their sold info is wiped out' .
                composeSuffix(['li' => $this->houseBidderLotItemIds])
            );
        }
    }
}
