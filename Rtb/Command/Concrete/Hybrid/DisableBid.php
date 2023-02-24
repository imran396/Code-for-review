<?php

namespace Sam\Rtb\Command\Concrete\Hybrid;

use Sam\Rtb\Hybrid\HelpersAwareTrait;
use Sam\Core\Constants;
use Sam\Rtb\Command\Helper\Hybrid\HybridRtbCommandHelperAwareTrait;

/**
 * Class DisableBid
 * @package Sam\Rtb\Command\Concrete\Hybrid
 */
class DisableBid extends \Sam\Rtb\Command\Concrete\Base\DisableBid
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
        $rtbCurrent = $this->getRtbCurrent();
        $rtbCurrent = $this->getRtbCommandHelper()->activateLot($rtbCurrent, Constants\Rtb::LA_IDLE, $this->detectModifierUserId());
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
        log_info('Bidding Disabled' . composeSuffix(['a' => $rtbCurrent->AuctionId]));
    }
}
