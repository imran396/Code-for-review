<?php
/**
 * SAM-5495: Rtb server and daemon refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           11/18/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\State\PendingAction;

use Sam\Core\Service\CustomizableClass;
use RtbCurrent;
use Sam\Core\Constants;
use Sam\Date\CurrentDateTrait;

/**
 * Class PendingActionUpdater
 * @package Sam\Rtb\State\PendingAction
 */
class PendingActionUpdater extends CustomizableClass
{
    use CurrentDateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Define pending action state
     * @param RtbCurrent $rtbCurrent
     * @param int|null $pendingAction
     * @return RtbCurrent
     */
    public function update(RtbCurrent $rtbCurrent, ?int $pendingAction): RtbCurrent
    {
        if ($pendingAction === null) {
            if ($rtbCurrent->PendingAction === Constants\Rtb::PA_SELECT_BUYER_BY_AGENT) {
                $rtbCurrent->BuyerUser = null;
            }
            $rtbCurrent->PendingActionDate = null;
        } else {
            $rtbCurrent->PendingActionDate = $this->getCurrentDateUtc();
        }
        $rtbCurrent->PendingAction = $pendingAction;
        return $rtbCurrent;
    }
}
