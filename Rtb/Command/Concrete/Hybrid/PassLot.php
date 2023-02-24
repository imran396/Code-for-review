<?php

namespace Sam\Rtb\Command\Concrete\Hybrid;

use LotItem;
use Sam\Rtb\Hybrid\HelpersAwareTrait;
use Sam\Rtb\Command\Helper\Hybrid\HybridRtbCommandHelperAwareTrait;

/**
 * Class PassLot
 * @package Sam\Rtb\Command\Concrete\Hybrid
 */
class PassLot extends \Sam\Rtb\Command\Concrete\Base\PassLot
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
        $this->getTimeoutHelper()->updateLotEndDate($this->getRtbCurrent(), $this->detectModifierUserId());
        $this->getResponseHelper()->applyAdditionalInfo($this);
    }

    /**
     * Implements next lot detection for hybrid auction.
     * Return null if no lots were found.
     * @return LotItem|null
     */
    protected function findNextLot(): ?LotItem
    {
        $rtbCurrent = $this->getRtbCurrent();
        $lotItem = $this->getRtbCommandHelper()->findNextLotItem($rtbCurrent);
        $this->isLastLot = !$lotItem;
        return $lotItem;
    }
}
