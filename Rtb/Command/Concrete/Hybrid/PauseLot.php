<?php

namespace Sam\Rtb\Command\Concrete\Hybrid;

use Sam\Rtb\Hybrid\HelpersAwareTrait;
use Sam\Rtb\Command\Helper\Hybrid\HybridRtbCommandHelperAwareTrait;

/**
 * Class PauseLot
 * @package Sam\Rtb\Command\Concrete\Hybrid
 */
class PauseLot extends \Sam\Rtb\Command\Concrete\Base\PauseLot
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
        /**
         * Do not update Lot End Date, when lot is paused, so we could restore existing countdown, when will un-pause lot (SAM-7651).
         */
        $this->getResponseHelper()->applyAdditionalInfo($this);
    }
}
