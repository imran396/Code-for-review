<?php
/**
 * SAM-6055: Rtbd running lot activity status
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 09, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\RtbCurrent\Status;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class RtbCurrentStatusPureChecker
 * @package Sam\Core\Entity\Model\RtbCurrent\Status
 */
class RtbCurrentStatusPureChecker extends CustomizableClass
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
     * Is running lot not active yet
     * @param int $lotActivityStatus
     * @return bool
     */
    public function isIdleLot(int $lotActivityStatus): bool
    {
        return $lotActivityStatus === Constants\Rtb::LA_IDLE;
    }

    /**
     * Is running lot started
     * @param int $lotActivityStatus
     * @return bool
     */
    public function isStartedLot(int $lotActivityStatus): bool
    {
        return $lotActivityStatus === Constants\Rtb::LA_STARTED;
    }

    /**
     * Is running lot paused
     * @param int $lotActivityStatus
     * @return bool
     */
    public function isPausedLot(int $lotActivityStatus): bool
    {
        return $lotActivityStatus === Constants\Rtb::LA_PAUSED;
    }

    /**
     * Is running lot started
     * @param int $lotActivityStatus
     * @return bool
     */
    public function isStartedOrPausedLot(int $lotActivityStatus): bool
    {
        return $this->isStartedLot($lotActivityStatus)
            || $this->isPausedLot($lotActivityStatus);
    }
}
