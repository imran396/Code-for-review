<?php

namespace Sam\Rtb\Command\Concrete\Hybrid;

use Sam\Core\Constants;
use Sam\Rtb\Hybrid\HelpersAwareTrait;
use Sam\Rtb\Command\Helper\Hybrid\HybridRtbCommandHelperAwareTrait;

/**
 * Class Sync
 * @package Sam\Rtb\Command\Concrete\Hybrid
 */
class Sync extends \Sam\Rtb\Command\Concrete\Base\Sync
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

    protected function createResponses(): void
    {
        parent::createResponses();
        $this->getResponseHelper()->applyAdditionalInfo($this);
    }

    /**
     * Add client's data about pending action
     * @param array $data
     * @return array
     */
    protected function addPendingActionData(array $data): array
    {
        $data = parent::addPendingActionData($data);
        $rtbCurrent = $this->getRtbCurrent();
        if ($rtbCurrent->PendingAction) {
            $data[Constants\Rtb::RES_PENDING_ACTION_SECOND_LEFT] = $this->getPendingActionSecondsLeft();
        }
        return $data;
    }

    /**
     * Return seconds left for pending action
     * @return int
     */
    protected function getPendingActionSecondsLeft(): int
    {
        $rtbCurrent = $this->getRtbCurrent();
        $pendingActionTimeout = $this->getSettingsManager()
            ->get(Constants\Setting::PENDING_ACTION_TIMEOUT_HYBRID, $this->getAuction()->AccountId);
        $secondsPassed = $this->getCurrentDateUtc()->getTimestamp() - $rtbCurrent->PendingActionDate->getTimestamp();
        $secondsLeft = $pendingActionTimeout - $secondsPassed;   // Pending Action Seconds Lefts
        return $secondsLeft;
    }
}
