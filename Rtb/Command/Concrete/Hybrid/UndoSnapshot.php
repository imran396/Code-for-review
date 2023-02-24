<?php

namespace Sam\Rtb\Command\Concrete\Hybrid;

use Sam\Core\Constants;
use Sam\Rtb\Hybrid\HelpersAwareTrait;
use Sam\Rtb\Command\Helper\Hybrid\HybridRtbCommandHelperAwareTrait;

/**
 * Class UndoSnapshot
 * @package Sam\Rtb\Command\Concrete\Hybrid
 */
class UndoSnapshot extends \Sam\Rtb\Command\Concrete\Base\UndoSnapshot
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
        $isResetTimerOnUndoHybrid = $this->getSettingsManager()
            ->get(Constants\Setting::RESET_TIMER_ON_UNDO_HYBRID, $this->getAuction()->AccountId);
        if ($isResetTimerOnUndoHybrid) {
            $this->getTimeoutHelper()->updateLotEndDate($this->getRtbCurrent(), $this->detectModifierUserId());
        }
    }

    protected function createResponses(): void
    {
        parent::createResponses();
        $this->getResponseHelper()->applyAdditionalInfo($this);
    }
}
