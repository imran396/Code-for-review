<?php
/**
 * VO represents status of Lot Bulk Grouping Role.
 * This class follows Immutable Value-Object pattern.
 * This role is defined by next states:
 * 1 - Master role (isBulkMaster = true, BulkMasterId === null / 0)
 * 2 - Piecemeal role (isBulkMaster = false, BulkMasterId > 0)
 * 3 - Non-grouped regular role (isBulkMaster = false, BulkMasterId === null / 0)
 *   - Non-grouped regular role (in case of ambiguous state: isBulkMaster = true, BulkMasterId > 0)
 *
 * SAM-6621: Enrich entity model for lot bulk group feature
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 13, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\AuctionLotItem\LotBulkGrouping;

use AuctionLotItem;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotBulkGrouping
 * @package Sam\Core\Entity\Model\AuctionLotItem
 */
class LotBulkGroupingRole extends CustomizableClass
{
    protected ?int $bulkMasterId;
    protected bool $isBulkMaster;

    // --- Constructors ---

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Construct new role from values.
     * By default it builds regular non-grouped role status.
     * @param int|null $masterAuctionLotId null means lot bulk group undefined
     * @param bool $isBulkMaster
     * @return $this
     */
    public function construct(?int $masterAuctionLotId, bool $isBulkMaster): static
    {
        $this->bulkMasterId = $masterAuctionLotId;
        $this->isBulkMaster = $isBulkMaster;
        return $this;
    }

    /**
     * Construct new master role
     * @return $this
     */
    public function constructMaster(): static
    {
        return $this->construct(null, true);
    }

    /**
     * Construct new piecemeal role assigned to specific lot bulk group
     * @param int|null $masterAuctionLotId
     * @return $this
     */
    public function constructPiecemeal(?int $masterAuctionLotId): static
    {
        return $this->construct($masterAuctionLotId, false);
    }

    /**
     * Construct for lot without bulk grouping.
     * @return $this
     */
    public function constructRegular(): static
    {
        return $this->construct(null, false);
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            $row['bulk_master_id'] ?? null,
            $row['is_bulk_master'] ?? false
        );
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @return $this
     */
    public function fromEntity(AuctionLotItem $auctionLot): static
    {
        return $this->construct(
            $auctionLot->BulkMasterId,
            $auctionLot->IsBulkMaster
        );
    }

    // --- VO modification command methods ---

    /**
     * Revoke bulk group info from auction lot
     * @return static
     */
    public function removeFromBulkGroup(): static
    {
        return self::new()->constructRegular();
    }

    /**
     * Changes auction lot status to be a piecemeal lot in bulk group for definite master lot
     * @param int $masterAuctionLotId
     * @return static
     */
    public function toPiecemealOfBulkGroup(int $masterAuctionLotId): static
    {
        return self::new()->construct($masterAuctionLotId, false);
    }

    /**
     * Flag auction lot as Master of some lot bulk group
     * @return static
     */
    public function toMasterOfBulkGroup(): static
    {
        return self::new()->constructMaster();
    }

    // --- Query methods ---

    /**
     * Checks, if auction lot is master for some lot bulk group
     * @return bool
     */
    public function isMaster(): bool
    {
        return $this->isBulkMaster
            // reference to another bulk group should absent
            && !$this->bulkMasterId;
    }

    /**
     * Checks, if auction lot is piecemeal lot in some lot bulk group.
     * @return bool
     */
    public function isPiecemeal(): bool
    {
        return $this->bulkMasterId > 0
            // master role flag should be unset
            && !$this->isBulkMaster;
    }

    /**
     * Check, if this bulk grouping piecemeal role relates to respective group defined by master auction lot
     * @param int|null $masterAuctionLotId
     * @return bool
     */
    public function isPiecemealInBulkGroup(?int $masterAuctionLotId): bool
    {
        return $this->isPiecemeal()
            && $this->bulkMasterId === $masterAuctionLotId;
    }

//    /**
//     * Check, if this piecemeal role lot is assigned to one of bulk groups referenced by master auction lot ids
//     * @param array $masterAuctionLotIds
//     * @return bool
//     */
//    public function isPiecemealInBulkGroups(array $masterAuctionLotIds): bool
//    {
//        if (!$this->isPiecemeal()) {
//            return false;
//        }
//        return in_array($this->bulkMasterId, $masterAuctionLotIds, true);
//    }

//    /**
//     * Check, if this is piecemeal role lot and it is NOT assigned to any of bulk groups referenced by master auction lot ids
//     * @param array $masterAuctionLotIds
//     * @return bool
//     */
//    public function isPiecemealSkipBulkGroups(array $masterAuctionLotIds): bool
//    {
//        if (!$this->isPiecemeal()) {
//            return false;
//        }
//        return !in_array($this->bulkMasterId, $masterAuctionLotIds, true);
//    }

    /**
     * Checks, if lot is assigned to some lot bulk group in role of master or piecemeal lot.
     * @return bool
     */
    public function inAnyBulkGroup(): bool
    {
        // Note, although it is OR-check, lot bulk grouping role still cannot be in both statuses simultaneously
        return $this->isMaster() || $this->isPiecemeal();
    }

    /**
     * @return int|null
     */
    public function detectMasterAuctionLotId(): ?int
    {
        return $this->bulkMasterId;
    }

    // --- Additional operations ---

    /**
     * Assign lot bulk grouping role values to entity
     * @param AuctionLotItem $auctionLot
     * @return AuctionLotItem
     */
    public function hydrate(AuctionLotItem $auctionLot): AuctionLotItem
    {
        $auctionLot->BulkMasterId = $this->bulkMasterId;
        $auctionLot->IsBulkMaster = $this->isBulkMaster;
        return $auctionLot;
    }

}
