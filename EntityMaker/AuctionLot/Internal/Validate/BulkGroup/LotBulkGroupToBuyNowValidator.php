<?php
/**
 * SAM-3103: bulk vs piecemeal and buy now
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Internal\Validate\BulkGroup;

use Sam\Core\Entity\Model\AuctionLotItem\LotBulkGrouping\LotBulkGroupingRole;
use Sam\Core\Service\CustomizableClass;

/**
 * Lot cannot be a member of Bulk Group and be enabled for Instant purchase in the same time
 *
 * Class LotBulkGroupToBuyNowValidator
 * @package Sam\EntityMaker\AuctionLot\Internal\Validate\BulkGroup
 * @internal
 */
class LotBulkGroupToBuyNowValidator extends CustomizableClass
{
    /**
     * @var bool
     */
    protected bool $isValidBuyNow = false;
    /**
     * @var bool
     */
    protected bool $isValidBulkGroup = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $isAssignedBuyNowAmount
     * @param float|null $buyNowAmount
     * @param float|null $actualByNowAmount
     * @param bool $isAssignedBulkControl
     * @param string|null $bulkControlValue
     * @param LotBulkGroupingRole|null $actualLotBulkGroupingRole
     * @return bool
     */
    public function validate(
        bool $isAssignedBuyNowAmount,
        ?float $buyNowAmount,
        ?float $actualByNowAmount,
        bool $isAssignedBulkControl,
        ?string $bulkControlValue,
        ?LotBulkGroupingRole $actualLotBulkGroupingRole
    ): bool {
        /**
         * First, check enabling of Buy Now feature for lot,
         * because its presence in Bulk Group has higher precedence.
         */
        $this->isValidBuyNow =
            !$isAssignedBuyNowAmount
            || !$buyNowAmount
            || !$this->isAuctionLotInBulkGroup($isAssignedBulkControl, $bulkControlValue, $actualLotBulkGroupingRole);

        $this->isValidBulkGroup = false;
        if ($this->isValidBuyNow) {
            $this->isValidBulkGroup =
                !$isAssignedBulkControl
                || !$bulkControlValue
                || !$this->isAuctionLotHasBuyNowAmount(
                    $isAssignedBuyNowAmount,
                    $buyNowAmount,
                    $actualByNowAmount
                );
        }
        return $this->isValidBuyNow && $this->isValidBulkGroup;
    }

    /**
     * @return bool
     */
    public function hasBulkGroupError(): bool
    {
        return !$this->isValidBulkGroup;
    }

    /**
     * @return bool
     */
    public function hasBuyNowError(): bool
    {
        return !$this->isValidBuyNow;
    }

    /**
     * Check if lot already is a member of Bulk Group, or will be assigned to Bulk Group in current action
     * @param bool $isAssignedBulkControl
     * @param string|null $bulkControlValue
     * @param LotBulkGroupingRole|null $actualLotBulkGroupingRole
     * @return bool
     */
    protected function isAuctionLotInBulkGroup(
        bool $isAssignedBulkControl,
        ?string $bulkControlValue,
        ?LotBulkGroupingRole $actualLotBulkGroupingRole
    ): bool {
        $isInBulkGroup = false;
        if ($isAssignedBulkControl) {
            $isInBulkGroup = (bool)$bulkControlValue;  // Check lot will become a member of Bulk Group
        } elseif ($actualLotBulkGroupingRole) {
            $isInBulkGroup = $actualLotBulkGroupingRole->inAnyBulkGroup();
        }
        return $isInBulkGroup;
    }

    /**
     * Check if lot already is marked for Instant purchase, or its Buy Now properties will be assigned in current action
     * @param bool $isAssignedBuyNowAmount
     * @param float|null $buyNowAmount
     * @param float|null $actualByNowAmount
     * @return bool
     */
    protected function isAuctionLotHasBuyNowAmount(
        bool $isAssignedBuyNowAmount,
        ?float $buyNowAmount,
        ?float $actualByNowAmount
    ): bool {
        $hasBuyNow = $isAssignedBuyNowAmount
            ? $buyNowAmount > 0  // Check lot will be ready for Instant purchase
            : $actualByNowAmount > 0;

        return $hasBuyNow;
    }
}
