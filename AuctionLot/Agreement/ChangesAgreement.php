<?php
/**
 * The class is used for checking user acceptance of lot changes. It gives accepted lot ids and it gives control to
 * reset lot.
 *
 * Related tickets:
 * SAM-3200: Refactor Lot Changes Confirmation checking feature
 *
 * Project        sam
 * Filename       ChangesAgreement.php
 * @author        Imran Rahman
 * @version       SVN: $Id: $
 * @since         Jan 28, 2016
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\AuctionLot\Agreement;

use AuctionLotItemChanges;
use Sam\AuctionLot\Load\AuctionLotItemChangesLoaderCreateTrait;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\DeleteRepository\Entity\AuctionLotItemChanges\AuctionLotItemChangesDeleteRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class ChangesAgreement
 * @package Sam\AuctionLot\Agreement
 */
class ChangesAgreement extends CustomizableClass
{
    use AuctionLotItemChangesDeleteRepositoryCreateTrait;
    use AuctionLotItemChangesLoaderCreateTrait;
    use AuctionLotItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     *
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return boolean user accepted lot changes or no.
     *
     * @param int|null $userId User->Id
     * @param int $lotItemId LotItem->Id
     * @param int $auctionId Auction->Id
     * @return bool
     */
    public function isAccepted(?int $userId, int $lotItemId, int $auctionId): bool
    {
        $auctionLotChanges = $this->createAuctionLotItemChangesLoader()->load($userId, $lotItemId, $auctionId);
        $isAccepted = $auctionLotChanges instanceof AuctionLotItemChanges;
        return $isAccepted;
    }

    /**
     * Return array auction lot item ids.
     *
     * @param int|null $userId User->Id
     * @param int $auctionId Auction->Id
     * @return int[]
     */
    public function loadAcceptedLotItemIds(?int $userId, int $auctionId): array
    {
        if (!$userId) {
            return [];
        }

        $rows = $this->createAuctionLotItemReadRepository()
            ->filterAuctionId($auctionId)
            ->joinLotItemSkipChanges('')
            ->joinAuctionLotItemChangesFilterUserId($userId)
            ->select(['ali.lot_item_id'])
            ->loadRows();
        $acceptedLotIds = ArrayCast::arrayColumnInt($rows, 'lot_item_id');
        return $acceptedLotIds;
    }

    /**
     * Reset existing agreements in auction_lot_item_changes
     *
     * @param int $lotItemId Lot Id
     * @return void
     */
    public function resetForLot(int $lotItemId): void
    {
        $this->createAuctionLotItemChangesDeleteRepository()
            ->filterLotItemId($lotItemId)
            ->delete();
    }

    /**
     * Checking lot changes or not
     *
     * @param bool $isAuctionRequired
     * @param string $lotChanges
     * @return bool
     */
    public function isRequired(bool $isAuctionRequired, string $lotChanges): bool
    {
        $isRequired = $isAuctionRequired && $lotChanges;
        return $isRequired;
    }
}
