<?php

namespace Sam\Rtb\Command\Concrete\Hybrid;

use Sam\Rtb\Hybrid\HelpersAwareTrait;
use Sam\Rtb\Command\Helper\Hybrid\HybridRtbCommandHelperAwareTrait;

/**
 * Class StartLot
 * @package Sam\Rtb\Command\Concrete\Hybrid
 */
class StartLot extends \Sam\Rtb\Command\Concrete\Base\StartLot
{
    use HelpersAwareTrait;
    use HybridRtbCommandHelperAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function execute(): void
    {
        parent::execute();
        $rtbCurrent = $this->getRtbCurrent();
        /**
         * Do not extend date when lot was paused, only if it is idle (SAM-7651).
         */
        if ($rtbCurrent->isIdleLot()) {
            $this->getTimeoutHelper()->updateLotEndDate($this->getRtbCurrent(), $this->detectModifierUserId());
        }
        $this->getResponseHelper()->applyAdditionalInfo($this);
    }
}
