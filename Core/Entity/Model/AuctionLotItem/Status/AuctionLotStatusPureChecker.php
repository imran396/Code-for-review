<?php
/**
 * Pure checker for different auction lot item statuses
 *
 * SAM-6827: Enrich AuctionLotItem entity
 * SAM-6822: Enrich entities
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\AuctionLotItem\Status;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class AuctionLotStatusPureChecker
 * @package Sam\Core\Entity\Model\AuctionLotItem\Status
 */
class AuctionLotStatusPureChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $lotStatus
     * @return bool
     */
    public function isActive(?int $lotStatus): bool
    {
        return $lotStatus === Constants\Lot::LS_ACTIVE;
    }

    /**
     * @param int|null $lotStatus
     * @return bool
     */
    public function isSold(?int $lotStatus): bool
    {
        return $lotStatus === Constants\Lot::LS_SOLD;
    }

    /**
     * @param int|null $lotStatus
     * @return bool
     */
    public function isUnsold(?int $lotStatus): bool
    {
        return $lotStatus === Constants\Lot::LS_UNSOLD;
    }

    /**
     * @param int|null $lotStatus
     * @return bool
     */
    public function isReceived(?int $lotStatus): bool
    {
        return $lotStatus === Constants\Lot::LS_RECEIVED;
    }

    /**
     * @param int|null $lotStatus
     * @return bool
     */
    public function isDeleted(?int $lotStatus): bool
    {
        return $lotStatus === Constants\Lot::LS_DELETED;
    }

    /**
     * @param int|null $lotStatus
     * @return bool
     */
    public function isActiveOrUnsold(?int $lotStatus): bool
    {
        return $this->isActive($lotStatus) || $this->isUnsold($lotStatus);
    }

    /**
     * @param int|null $lotStatus
     * @return bool
     */
    public function isAmongAvailableLotStatuses(?int $lotStatus): bool
    {
        return in_array($lotStatus, Constants\Lot::$availableLotStatuses, true);
    }

    /**
     * @param int|null $lotStatus
     * @return bool
     */
    public function isAmongWonStatuses(?int $lotStatus): bool
    {
        return in_array($lotStatus, Constants\Lot::$wonLotStatuses, true);
    }

    /**
     * @param int|null $lotStatus
     * @return bool
     */
    public function isAmongClosedStatuses(?int $lotStatus): bool
    {
        return in_array($lotStatus, Constants\Lot::$closedLotStatuses, true);
    }
}
